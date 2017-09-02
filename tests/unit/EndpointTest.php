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


class EndpointTest extends PHPUnit_Framework_TestCase
{
    public function testNotInit()
    {
        $this->expectException(BadMethodCallException::class);
        \easyops\easykin\Endpoint::toArray();
    }

    public function testInit()
    {
        \easyops\easykin\Endpoint::init('service', '127.0.0.1', 80);
        $endpoint = \easyops\easykin\Endpoint::toArray();
        $this->assertEquals('service', $endpoint['serviceName']);
        $this->assertEquals('127.0.0.1', $endpoint['ipv4']);
        $this->assertEquals(80, $endpoint['port']);
    }
}
