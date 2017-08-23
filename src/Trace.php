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
     * @return ClientSpan
     */
    public function newSpan($name)
    {
        $span = new ClientSpan($name, $this->serverSpan->traceId, $this->serverSpan->id);
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