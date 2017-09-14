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


/**
 * Class HttpTrace
 * @package easyops\easykin
 */
class HttpTrace extends Trace
{
    /**
     * HttpTrace constructor.
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        $url = parse_url(self::getVar('REQUEST_URI', '/'));
        $name = self::getVar('REQUEST_METHOD', 'GET').':'.$url['path'];
        $traceId = self::getVar('HTTP_X_B3_TRACEID', null);
        $parentSpanId = self::getVar('HTTP_X_B3_PARENTSPANID', null);
        $spanId = self::getVar('HTTP_X_B3_SPANID', null);
        $sampled = self::getVar('HTTP_X_B3_SAMPLED', null);
        parent::__construct($name, $sampled, $traceId, $parentSpanId, $spanId);
    }

    /**
     * @param string $var
     * @param mixed $default
     * @return string
     */
    public static function getVar($var, $default = '') {
        return isset($_SERVER[$var]) ? $_SERVER[$var] : $default;
    }

}