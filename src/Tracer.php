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
    protected $trace;

    /** @var Logger $logger */
    protected $logger;

    /**
     * Tracer constructor.
     * @param Trace $trace
     * @param Logger $logger
     */
    public function __construct($trace, $logger)
    {
        $this->trace = $trace;
        $this->logger = $logger;
    }

    public function trace()
    {
        if (!is_null($this->trace) && !is_null($this->logger)) {
            $this->trace->trace($this->logger);
        }
        $this->trace = null;
        $this->logger = null;
    }

    public function __destruct()
    {
        $this->trace();
    }
}