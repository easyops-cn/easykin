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

use easyops\easykin\logger\Logger;


/**
 * Class Tracer
 * @package easyops\easykin
 */
class Tracer
{
    /** @var Trace $trace */
    public $trace;

    /** @var Logger $logger */
    public $logger;

    /**
     * Tracer constructor.
     * @param Trace $trace
     * @param Logger $logger
     */
    public function __construct($trace = null, $logger = null)
    {
        $this->trace = $trace;
        $this->logger = $logger;
    }

    /**
     * @param Trace $trace
     */
    public function setTrace($trace)
    {
        $this->trace = $trace;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function newSpan($name, $serviceName = null, $ipv4 = null, $port = null)
    {
        return $this->trace->newSpan($name, $serviceName, $ipv4, $port);
    }

    public function isSampled()
    {
        return $this->trace->sampled;
    }

    public function drop()
    {
        $this->trace = null;
        $this->logger = null;
    }

    public function trace()
    {
        if (!is_null($this->trace) && !is_null($this->logger)) {
            $this->trace->trace($this->logger);
            $this->drop();
        }
    }

    public function __destruct()
    {
        $this->trace();
    }
}