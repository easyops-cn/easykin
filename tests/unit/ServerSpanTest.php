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


class ServerSpanTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \easyops\easykin\Endpoint::init('service', '127.0.0.1', 80);
        parent::setUp();
    }

    public function testNewSpan()
    {
        $traceId = 'b839bdf6661687d00a5e0cd93ceb0fdc';
        $parentSpanId = '0ac6363774ea1973';
        $spanId = '0ac6363774ea1973';
        $span = new \easyops\easykin\ServerSpan('get:index.php', $traceId, $parentSpanId, $spanId);
        $this->assertNotNull($span->id);
        $span->receive();
        $data = $span->toArray();
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('traceId', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('timestamp', $data);
        $this->assertArrayHasKey('duration', $data);
        $this->assertArrayHasKey('annotations', $data);
        $this->assertArrayHasKey('binaryAnnotations', $data);
    }

}
