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


class HttpTraceTest extends PHPUnit_Framework_TestCase
{
    public function testNewFrontendHttpTrace()
    {
        $_SERVER = [
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/index.php',
        ];
        $trace = new \easyops\easykin\core\HttpTrace();
        $this->assertEquals(1, $trace->sampled);
        $this->assertNotNull($trace->serverSpan->traceId);
        $this->assertNotNull($trace->serverSpan->id);
        $this->assertNull($trace->serverSpan->parentId);
    }

    public function testNewBackendHttpTrace()
    {
        $_SERVER = [
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/index.php',
            'HTTP_X_B3_TRACEID' => 'b839bdf6661687d00a5e0cd93ceb0fdc',
            'HTTP_X_B3_PARENTSPANID' => '0ac6363774ea1973',
            'HTTP_X_B3_SPANID' => '0ac6363774ea1973',
            'HTTP_X_B3_SAMPLED' => '1',
        ];
        $trace = new \easyops\easykin\core\HttpTrace();
        $this->assertEquals(1, $trace->sampled);
        $this->assertNotNull($trace->serverSpan->traceId);
        $this->assertNotNull($trace->serverSpan->id);
        $this->assertNotNull($trace->serverSpan->parentId);
        $this->assertEquals($_SERVER['HTTP_X_B3_SPANID'], $trace->serverSpan->id);
    }

}
