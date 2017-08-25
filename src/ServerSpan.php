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
 * Class ServerSpan
 * @package anyclouds\easykin
 */
class ServerSpan extends AbstractSpan
{
    /**
     * ServerSpan constructor.
     * @param string $name
     * @param string|null $traceId
     * @param string|null $parentId
     * @param string|null $id
     */
    public function __construct($name, $traceId = null, $parentId = null, $id = null)
    {
        is_null($traceId) && $traceId = bin2hex(openssl_random_pseudo_bytes(16));
        is_null($id) && $id = bin2hex(openssl_random_pseudo_bytes(8));
        parent::__construct($name, $traceId, $parentId, $id);
    }

    /**
     * @return string
     */
    protected function valueOfStart()
    {
        return 'sr';
    }

    /**
     * @return string
     */
    protected function valueOfEnd()
    {
        return 'ss';
    }
}