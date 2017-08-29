<?php
/**
 * Load composer's autoload
 */

require_once '../../vendor/autoload.php';

const SERVICE_NAME = 'console';
const SERVICE_IP = '127.0.0.1';
const SERVICE_PORT = '8000';
const LOGIN_SERVICE_NAME = 'login_service';
const LOGIN_SERVICE_IP = '127.0.0.1';
const LOGIN_SERVICE_PORT = '8001';
const BUSINESS_SERVICE_NAME = 'business_service';
const BUSINESS_SERVICE_IP = '127.0.0.1';
const BUSINESS_SERVICE_PORT = '8002';

\easyops\easykin\Endpoint::init(SERVICE_NAME, SERVICE_IP, SERVICE_PORT);


class Process
{
    public static function main()
    {
        $request = '';

//-------------------- service a ---------------------------
        $url = 'http://'.LOGIN_SERVICE_IP.':'.LOGIN_SERVICE_PORT.'/login.php';
        $span = \easyops\easykin\HttpTracer::newSpan('get:/login.php', LOGIN_SERVICE_NAME, LOGIN_SERVICE_IP, LOGIN_SERVICE_PORT);
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' =>
                    'X-B3-TraceId: ' . $span->traceId . "\r\n" .
                    'X-B3-SpanId: ' . $span->id . "\r\n" .
                    'X-B3-ParentSpanId: ' . $span->parentId . "\r\n" .
                    'X-B3-Sampled: ' . \easyops\easykin\HttpTracer::$sampled . "\r\n"
            ]
        ]);
        $request .= file_get_contents($url, false, $context);
        $span->receive();

//-------------------- service b ---------------------------
        $url = 'http://'.BUSINESS_SERVICE_IP.':'.BUSINESS_SERVICE_PORT.'/business.php';
        $span = \easyops\easykin\HttpTracer::newSpan('get:/business.php', BUSINESS_SERVICE_NAME, BUSINESS_SERVICE_IP, BUSINESS_SERVICE_PORT);
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' =>
                    'X-B3-TraceId: ' . $span->traceId . "\r\n" .
                    'X-B3-SpanId: ' . $span->id . "\r\n" .
                    'X-B3-ParentSpanId: ' . $span->parentId . "\r\n" .
                    'X-B3-Sampled: ' . \easyops\easykin\HttpTracer::$sampled . "\r\n"
            ]
        ]);
        $request .= file_get_contents($url, false, $context);
        $span->receive();

        echo "index2.php\n" . $request;
    }
}


\easyops\easykin\HttpTracer::trace(function() {Process::main();}, null, new easyops\easykin\logger\HttpLogger('http://192.168.100.165:9411/api/v1/spans', false));
