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


class FileLoggerTest extends PHPUnit_Framework_TestCase
{
    public function testMuteError()
    {
        $logger = new \easyops\easykin\logger\FileLogger('tmp', 'zipkin.log');
        $logger->log([]);
        $this->assertTrue(true);
    }

    public function testDirInvalid()
    {
        $this->expectException(\easyops\easykin\logger\LoggerException::class);
        $logger = new \easyops\easykin\logger\FileLogger('tmp', 'zipkin.log', false);
        $logger->log([]);
    }

    public function testFileInvalid()
    {
        $this->expectException(\easyops\easykin\logger\LoggerException::class);
        $logger = new \easyops\easykin\logger\FileLogger('.', 'readonly.log', false);
        $logger->log([]);
    }

    public function testFileOK()
    {
        $logger = new \easyops\easykin\logger\FileLogger('.', 'zipkin.log', false);
        $logger->log([]);
        $this->assertTrue(true);
    }

}
