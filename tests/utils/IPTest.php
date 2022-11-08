<?php

namespace easyops\easykin\tests\utils;

use easyops\easykin\utils\IP;
use PHPUnit\Framework\TestCase;

class IPTest extends TestCase
{
    public function testIsIPv6()
    {
        $cases = [
            [
                'ip' => '0.0.0.0',
                'expect' => false,
            ],
            [
                'ip' => '127.0.0.1',
                'expect' => false,
            ],
            [
                'ip' => '110.242.68.66',
                'expect' => false,
            ],
            // IPv6
            [
                'ip' => '::',
                'expect' => true,
            ],
            [
                'ip' => '::1',
                'expect' => true,
            ],
            [
                'ip' => 'fe80::216:3eff:fe78:4bc6',
                'expect' => true,
            ],
        ];
        foreach ($cases as $case) {
            self::assertEquals($case['expect'], IP::isIPv6($case['ip']));
        }
    }
}
