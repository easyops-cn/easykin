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

    /** @var array $binaryAnnotations */
    protected $binaryAnnotations = [];

    /**
     * Span constructor.
     * @param string $name
     * @param string $traceId
     * @param string $id
     * @param string|null $parentId
     */
    public function __construct($name, $traceId, $id, $parentId = null)
    {
        $this->name = $name;
        $this->traceId = $traceId;
        $this->id = $id;
        $this->parentId = $parentId;
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
            'binaryAnnotations' => $this->binaryAnnotations,
        ];

        if (!is_null($this->parentId)) {
            $span['parentId'] = (string) $this->parentId;
        }

        return $span;

    }

}