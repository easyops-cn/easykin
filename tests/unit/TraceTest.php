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


class TraceTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \easyops\easykin\core\Endpoint::init('service', '127.0.0.1', 80);
        parent::setUp();
    }

    public function testNewFrontendTrace()
    {
        $trace = new \easyops\easykin\core\Trace('frontend get:index.php');
        $this->assertEquals(1, $trace->sampled);
        $this->assertNotNull($trace->serverSpan->traceId);
        $this->assertNotNull($trace->serverSpan->id);
        $this->assertNull($trace->serverSpan->parentId);
    }

    public function testNewBackendTrace()
    {
        $traceId = 'b839bdf6661687d00a5e0cd93ceb0fdc';
        $parentSpanId = '0ac6363774ea1973';
        $spanId = '0ac6363774ea1973';
        $trace = new \easyops\easykin\core\Trace('frontend get:index.php', '1', $traceId, $parentSpanId, $spanId);
        $this->assertEquals(1, $trace->sampled);
        $this->assertNotNull($trace->serverSpan->traceId);
        $this->assertNotNull($trace->serverSpan->id);
        $this->assertNotNull($trace->serverSpan->parentId);
        $this->assertEquals($spanId, $trace->serverSpan->id);
    }

    public function testNewSpan()
    {
        $trace = new \easyops\easykin\core\Trace('frontend get:index.php');
        $span = $trace->newSpan('get:index.php', 'business', '127.0.0.1', 8080);
        $this->assertNotNull($span->parentId);
        $this->assertNotNull($span->id);
        $this->assertNotNull($span->traceId);
        $this->assertEquals($trace->serverSpan->traceId, $span->traceId);
        $this->assertEquals($trace->serverSpan->id, $span->parentId);
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
