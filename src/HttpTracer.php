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

use easyops\easykin\logger\FileLogger;
use easyops\easykin\logger\Logger;


/**
 * Class HttpTracer
 * @package easyops\easykin
 */
class HttpTracer
{
    /** @var Trace $trace */
    protected static $trace;

    /** @var int $sampled */
    public static $sampled;

    /**
     * @param callable $callable
     * @param string|null $name
     * @param Logger|null $logger
     */
    public static function trace($callable, $name = null, $logger = null)
    {
        is_null($name) && $name = strtolower($_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI']);
        is_null($logger) && $logger = new FileLogger('.');
        $traceId = isset($_SERVER['HTTP_X_B3_TRACEID']) ? $_SERVER['HTTP_X_B3_TRACEID'] : null;
        $parentSpanId = isset($_SERVER['HTTP_X_B3_PARENTSPANID']) ? $_SERVER['HTTP_X_B3_PARENTSPANID'] : null;
        $spanId = isset($_SERVER['HTTP_X_B3_SPANID']) ? $_SERVER['HTTP_X_B3_SPANID'] : null;
        $sampled = isset($_SERVER['HTTP_X_B3_SAMPLED']) ? $_SERVER['HTTP_X_B3_SAMPLED'] : null;
        static::$trace = new Trace($name, $sampled, $traceId, $parentSpanId, $spanId);
        static::$sampled = static::$trace->sampled;
        static::process($callable);
        static::$trace->trace($logger);
    }

    /**
     * @param callable $callable
     */
    protected static function process($callable)
    {
        try {
            $callable();
        }
        catch (\Exception $exception) {
            static::$trace->serverSpan->tag(ServerSpan::TAG_ERROR, $exception->getMessage());
        }
    }

    /**
     * @param string $name
     * @param string|null $serviceName
     * @param string|null $ipv4
     * @param int|null $port
     * @return ClientSpan
     */
    public static function newSpan($name, $serviceName = null, $ipv4 = null, $port = null)
    {
        return static::$trace->newSpan($name, $serviceName, $ipv4, $port);
    }

}