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

/**
 * Class EasyKin
 * @package easyops\easykin
 * @method static void setTrace(easyops\easykin\Trace $trace)
 * @method static void setLogger(easyops\easykin\logger\Logger $logger)
 * @method static easyops\easykin\ClientSpan newSpan($name, $serviceName = null, $ipv4 = null, $port = null)
 * @method static int isSampled()
 * @method static void trace()
 */
class EasyKin
{
    /** @var easyops\easykin\Tracer $tracer */
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
     * @return easyops\easykin\Tracer
     */
    public static function app() {
        static $initialized = false;
        if (!$initialized) {
            static::$tracer = new easyops\easykin\Tracer();
            $initialized = true;
        }
        return static::$tracer;
    }
}