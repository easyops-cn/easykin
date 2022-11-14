<?php
/**
 * @author index
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

namespace easyops\easykin\core;

use easyops\easykin\logger\Logger;


/**
 * Class Trace
 * @package anyclouds\easykin
 */
class Trace
{
    /** @var array $spans */
    public $spans = [];

    /** @var ServerSpan $serverSpan */
    public $serverSpan;

    /** @var int $sampled */
    public $sampled;

    /**
     * Trace constructor.
     * @param string $name
     * @param int|null $sampled
     * @param string|null $traceId
     * @param string|null $parentSpanId
     * @param string|null $spanId
     */
    public function __construct($name, $sampled = null, $traceId = null, $parentSpanId = null, $spanId = null)
    {
        $this->sampled = is_null($sampled) ? 1 : intval($sampled);
        $this->serverSpan = new ServerSpan($name, $traceId, $parentSpanId, $spanId);
    }

    /**
     * @param string $name
     * @param string|null $serviceName
     * @param string|null $ip
     * @param int|null $port
     * @return ClientSpan
     */
    public function newSpan($name, $serviceName = null, $ip = null, $port = null)
    {
        $span = new ClientSpan($name, $this->serverSpan->traceId, $this->serverSpan->id);
        !is_null($serviceName) && !is_null($ip) && !is_null($port) && $span->setSA($serviceName, $ip, $port);
        $this->spans[] = $span;
        return $span;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $this->serverSpan->receive();
        $spans = [$this->serverSpan->toArray()];
        /** @var AbstractSpan $span */
        foreach ($this->spans as $span) {
            $spans[] = $span->toArray();
        }
        return $spans;
    }

    /**
     * @param Logger $logger
     */
    public function trace($logger)
    {
        if (boolval($this->sampled)) {
            $logger->log($this->toArray());
        }
    }

}