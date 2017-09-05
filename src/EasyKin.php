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
 * Class EasyKin
 * @package easyops\easykin
 * @method static void setTrace(Trace $trace)
 * @method static void setLogger(Logger $logger)
 * @method static ClientSpan newSpan($name, $serviceName = null, $ipv4 = null, $port = nul)
 * @method static int isSampled()
 * @method static void trace()
 */
class EasyKin
{
    /** @var Tracer $tracer */
    protected static $tracer;

    /**
     * @param string $name
     * @param mixed $params
     * @return mixed
     */
    public static function __callStatic($name, $params)
    {
        $tracer = static::app();
        return call_user_func_array([$tracer, $name], $params);
    }

    /**
     * @return Tracer
     */
    public static function app() {
        static $initialized = false;
        if (!$initialized) {
            self::$tracer = new Tracer();
            $initialized = true;
        }
        return self::$tracer;
    }
}