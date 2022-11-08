<?php

namespace easyops\easykin\utils;

class IP
{
    public static function isIPv6($ip)
    {
        return \filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }
}