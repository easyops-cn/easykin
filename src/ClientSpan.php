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
        parent::__construct($name, $traceId, bin2hex(openssl_random_pseudo_bytes(8)), $parentId);
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
     * @param string $ipv4
     * @param int $port
     */
    public function setSA($serviceName, $ipv4, $port)
    {
        $this->sa = [
            'key' => 'sa',
            'value' => true,
            'endpoint' => [
                'serviceName' => $serviceName,
                'ipv4' => $ipv4,
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