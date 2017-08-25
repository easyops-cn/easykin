<?php
/**
 * @author
 *   ┏┓   ┏┓+ +
 *  ┏┛┻━━━┛┻┓ + +
 *  ┃       ┃
 *  ┃  ━    ┃ ++ + + +
 * ████━████┃+
 *  ┃       ┃ +
 *  ┃  ┻    ┃
 *  ┃       ┃ + +
 *  ┗━┓   ┏━┛
 *    ┃   ┃
 *    ┃   ┃ + + + +
 *    ┃   ┃     Codes are far away from bugs with the animal protecting
 *    ┃   ┃ +         神兽保佑,代码无bug
 *    ┃   ┃
 *    ┃   ┃   +
 *    ┃   ┗━━━┓ + +
 *    ┃       ┣┓
 *    ┃       ┏┛
 *    ┗┓┓┏━┳┓┏┛ + + + +
 *     ┃┫┫ ┃┫┫
 *     ┗┻┛ ┗┻┛+ + + +
 */

namespace easyops\easykin;


/**
 * Class AbstractSpan
 * @package anyclouds\easykin
 */
abstract class AbstractSpan
{
    /** @var string $name */
    protected $name;

    /** @var string $traceId */
    public $traceId;

    /** @var string $id */
    public $id;

    /** @var string $parentId */
    public $parentId;

    /** @var int $timestamp */
    protected $timestamp;

    /** @var int $duration */
    protected $duration;

    /** @var array $annotations */
    protected $annotations = [];

    /** @var array $tags */
    protected $tags = [];

    const TAG_HTTP_HOST = 'http.host';
    const TAG_HTTP_METHOD = 'http.method';
    const TAG_HTTP_PATH = 'http.path';
    const TAG_HTTP_URL = 'http.url';
    const TAG_HTTP_STATUS_CODE = 'http.status_code';
    const TAG_ERROR = 'error';
    const TAG_LOCAL_COMPONENT = 'lc';

    /**
     * Span constructor.
     * @param string $name
     * @param string $traceId
     * @param string|null $parentId
     * @param string $id
     */
    public function __construct($name, $traceId, $parentId, $id)
    {
        $this->name = $name;
        $this->traceId = $traceId;
        $this->parentId = $parentId;
        $this->id = $id;
        $this->timestamp = intval(microtime(true) * 1000 * 1000);
    }

    public function receive()
    {

        $startTimestamp = $this->timestamp;
        $endTimestamp = intval(microtime(true) * 1000 * 1000);
        $this->duration = $endTimestamp - $startTimestamp;

        $this->annotations = [
            [
                'endpoint' => Endpoint::toArray(),
                'timestamp' => $startTimestamp,
                'value' => $this->valueOfStart()
            ],
            [
                'endpoint' => Endpoint::toArray(),
                'timestamp' => $endTimestamp,
                'value' => $this->valueOfEnd()
            ]
        ];
    }

    /**
     * @return string
     */
    abstract protected function valueOfStart();

    /**
     * @return string
     */
    abstract protected function valueOfEnd();

    /**
     * @return array
     */
    public function toArray()
    {
        $span = [
            'id' => (string) $this->id,
            'traceId' => (string) $this->traceId,
            'name' => $this->name,
            'timestamp' => $this->timestamp,
            'duration' => $this->duration,
            'annotations' => $this->annotations,
            'binaryAnnotations' => $this->getBinaryAnnotations(),
        ];

        if (!is_null($this->parentId)) {
            $span['parentId'] = (string) $this->parentId;
        }

        return $span;
    }

    /**
     * @param string $key
     * @param string|int|float|bool $value
     * @throws \InvalidArgumentException
     */
    public function tag($key, $value)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('$key must be string');
        }

        if (!is_string($value) && !is_numeric($value) && !is_bool($value)) {
            throw new \InvalidArgumentException('$value must be string, int or bool');
        }

        $this->tags[$key] = (string) $value;
    }

    /**
     * @return array
     */
    protected function getBinaryAnnotations()
    {
        $annotations = [];
        foreach ($this->tags as $key => $value) {
            $annotations[] = [
                'key' => $key,
                'value' => $value,
                'endpoint' => Endpoint::toArray()
            ];
        }
        return $annotations;
    }

}