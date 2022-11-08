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
 * Class ClientSpan
 * @package anyclouds\easykin
 */
class ClientSpan extends AbstractSpan
{
    /** @var array $sa */
    protected $sa = [];

    /**
     * Span constructor.
     * @param string $name
     * @param string $traceId
     * @param string $parentId
     */
    public function __construct($name, $traceId, $parentId)
    {
        parent::__construct($name, $traceId, $parentId, bin2hex(openssl_random_pseudo_bytes(8)));
    }

    /**
     * @return string
     */
    protected function valueOfStart()
    {
        return 'cs';
    }

    /**
     * @return string
     */
    protected function valueOfEnd()
    {
        return 'cr';
    }

    /**
     * @param string $serviceName
     * @param string $ip
     * @param int $port
     */
    public function setSA($serviceName, $ip, $port)
    {
        $isIPv6 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        $this->sa = [
            'key' => 'sa',
            'value' => true,
            'endpoint' => [
                'serviceName' => $serviceName,
                'ipv4' => !$isIPv6 ? $ip : null,
                'ipv6' => $isIPv6 ? $ip : null,
                'port' => $port
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getBinaryAnnotations()
    {
        unset($this->tags['sa']);
        $binaryAnnotations = parent::getBinaryAnnotations();
        if (!empty($this->sa)) {
            $binaryAnnotations[] = $this->sa;
        }
        return $binaryAnnotations;
    }

}