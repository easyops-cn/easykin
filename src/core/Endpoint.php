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

use easyops\easykin\utils\IP;
use InvalidArgumentException;

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
     * @param string $ip
     * @param int $port
     */
    public static function init($serviceName, $ip, $port)
    {
        if ($isIPv6 = IP::isIPv6($ip)) {
            if ($ip !== null && \filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                throw new InvalidArgumentException(
                    \sprintf('Invalid IPv6 %s', $ip)
                );
            }
        } else {
            if ($ip !== null && \filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                throw new InvalidArgumentException(
                    \sprintf('Invalid IPv4. Expected something in the range 0.0.0.0 and 255.255.255.255, got %s', $ip)
                );
            }
        }
        if ($port !== null) {
            if ($port > 65535) {
                throw new InvalidArgumentException(
                    \sprintf('Invalid port. Expected a number between 0 and 65535, got %d', $port)
                );
            }
        }
        static::$endpoint = [
            'serviceName' => $serviceName,
            'ipv4' => !$isIPv6 ? $ip : null,
            'ipv6' => $isIPv6 ? $ip : null,
            'port' => $port,
        ];
    }

    /**
     * @return array
     */
    public static function toArray()
    {
        if (!isset(static::$endpoint['serviceName']) ||
            (!isset(static::$endpoint['ipv4']) && !isset(static::$endpoint['ipv6'])) ||
            !isset(static::$endpoint['port'])) {
            throw new \BadMethodCallException('Endpoint not initialized.');
        }
        return static::$endpoint;
    }
}