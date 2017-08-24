<?php
/**
 * Load composer's autoload
 */

use whitemerry\phpkin\Logger\SimpleHttpLogger;

require_once '../../vendor/autoload.php';

const SERVICE_NAME = 'console';
const SERVICE_IP = '127.0.0.1';
const SERVICE_PORT = '8000';
const SERVER_A_NAME = 'service_a';
const SERVER_A_IP = '127.0.0.1';
const SERVER_A_PORT = '8001';
const SERVER_B_NAME = 'service_b';
const SERVER_B_IP = '127.0.0.1';
const SERVER_B_PORT = '8002';


\easyops\easykin\Endpoint::init(SERVICE_NAME, SERVICE_IP, SERVICE_PORT);

$trace = new \easyops\easykin\Trace(new \easyops\easykin\ServerSpan('get /index.php'));

$request = '';

sleep(1);

//-------------------- service a ---------------------------
$url = 'http://'.SERVER_A_IP.':'.SERVER_A_PORT.'/index.php';
$span = $trace->newSpan('get /index.php', SERVER_A_NAME, SERVER_A_IP, SERVER_A_PORT);
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' =>
            'X-B3-TraceId: ' . $span->traceId . "\r\n" .
            'X-B3-SpanId: ' . $span->id . "\r\n" .
            'X-B3-ParentSpanId: ' . $span->parentId . "\r\n"
    ]
]);
$request .= file_get_contents($url, false, $context);
$span->receive();

//-------------------- service b ---------------------------
$url = 'http://'.SERVER_B_IP.':'.SERVER_B_PORT.'/index.php';
$span = $trace->newSpan('get /index.php', SERVER_B_NAME, SERVER_B_IP, SERVER_B_PORT);
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' =>
            'X-B3-TraceId: ' . $span->traceId . "\r\n" .
            'X-B3-SpanId: ' . $span->id . "\r\n" .
            'X-B3-ParentSpanId: ' . $span->parentId . "\r\n"
    ]
]);
$request .= file_get_contents($url, false, $context);
$span->receive();

sleep(1);

echo SERVICE_NAME.' get /index.php: <br>' . $request;

$logger = new SimpleHttpLogger(['host' => 'http://192.168.100.165:9411', 'muteErrors' => false]);
$logger->trace($trace->toArray());
