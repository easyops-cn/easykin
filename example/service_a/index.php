<?php
/**
 * Load composer's autoload
 */
require_once '../../vendor/autoload.php';

use whitemerry\phpkin\Tracer;
use whitemerry\phpkin\Endpoint;
use whitemerry\phpkin\Span;
use whitemerry\phpkin\Identifier\SpanIdentifier;
use whitemerry\phpkin\Identifier\TraceIdentifier;
use whitemerry\phpkin\AnnotationBlock;
use whitemerry\phpkin\Logger\SimpleHttpLogger;
use whitemerry\phpkin\TracerInfo;

const SERVICE_NAME = 'service_a';
const SERVICE_IP = '127.0.0.1';
const SERVICE_PORT = '8001';
const SERVER_C_NAME = 'service_c';
const SERVER_C_IP = '127.0.0.1';
const SERVER_C_PORT = '8003';

\easyops\easykin\Endpoint::init(SERVICE_NAME, SERVICE_IP, SERVICE_PORT);

$traceId = !empty($_SERVER['HTTP_X_B3_TRACEID']) ? $_SERVER['HTTP_X_B3_TRACEID'] : null;
$parentSpanId = !empty($_SERVER['HTTP_X_B3_PARENTSPANID']) ? $_SERVER['HTTP_X_B3_PARENTSPANID'] : null;
$spanId = !empty($_SERVER['HTTP_X_B3_SPANID']) ? $_SERVER['HTTP_X_B3_SPANID'] : null;

$trace = new \easyops\easykin\Trace(new \easyops\easykin\ServerSpan('get /index.php', $traceId, $spanId, $parentSpanId));

sleep(1);

$url = 'http://'.SERVER_C_IP.':'.SERVER_C_PORT.'/index.php';
$span = $trace->newSpan('get /index.php', SERVER_C_NAME, SERVER_C_IP, SERVER_C_PORT);
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' =>
            'X-B3-TraceId: ' . $span->traceId . "\r\n" .
            'X-B3-SpanId: ' . $span->id . "\r\n" .
            'X-B3-ParentSpanId: ' . $span->parentId . "\r\n"
    ]
]);
$request = file_get_contents($url, false, $context);
$span->receive();

sleep(1);

echo SERVICE_NAME.' get /index.php: <br>' . $request;

$logger = new SimpleHttpLogger(['host' => 'http://192.168.100.165:9411', 'muteErrors' => false]);
$logger->trace($trace->toArray());
