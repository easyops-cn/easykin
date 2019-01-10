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

use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    public function testNotInit()
    {
        $this->expectException(\BadMethodCallException::class);
        \easyops\easykin\core\Endpoint::toArray();
    }

    public function testInit()
    {
        \easyops\easykin\core\Endpoint::init('service', '127.0.0.1', 80);
        $endpoint = \easyops\easykin\core\Endpoint::toArray();
        $this->assertEquals('service', $endpoint['serviceName']);
        $this->assertEquals('127.0.0.1', $endpoint['ipv4']);
        $this->assertEquals(80, $endpoint['port']);
    }
}
