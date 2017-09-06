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
 * Class Endpoint
 * @package anyclouds\easykin
 */
class Endpoint
{
    /** @var array $endpoint */
    protected static $endpoint = [];

    /**
     * Endpoint constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param string $serviceName
     * @param string $ipv4
     * @param int $port
     */
    public static function init($serviceName, $ipv4, $port)
    {
        static::$endpoint = [
            'serviceName' => $serviceName,
            'ipv4' => $ipv4,
            'port' => $port,
        ];
    }

    /**
     * @return array
     */
    public static function toArray()
    {
        if (!isset(static::$endpoint['serviceName']) ||
            !isset(static::$endpoint['ipv4']) ||
            !isset(static::$endpoint['port'])) {
            throw new \BadMethodCallException('Endpoint not initialized.');
        }
        return static::$endpoint;
    }
}