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

namespace easyops\easykin;


/**
 * Class Trace
 * @package anyclouds\easykin
 */
class Trace
{
    /** @var array $spans */
    protected $spans = [];

    /** @var ServerSpan $serverSpan */
    protected $serverSpan;

    /**
     * Trace constructor.
     * @param ServerSpan $serverSpan
     */
    public function __construct($serverSpan)
    {
        $this->serverSpan = $serverSpan;
    }

    /**
     * @param string $name
     * @param string|null $serviceName
     * @param string|null $ipv4
     * @param int|null $port
     * @return ClientSpan
     */
    public function newSpan($name, $serviceName = null, $ipv4 = null, $port = null)
    {
        $span = new ClientSpan($name, $this->serverSpan->traceId, $this->serverSpan->id);
        !is_null($serviceName) && !is_null($ipv4) && !is_null($port) && $span->setSA($serviceName, $ipv4, $port);
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

}