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

const SERVICE_NAME = 'login_service';
const SERVICE_IP = '127.0.0.1';
const SERVICE_PORT = '8001';
const MYSQL_PROXY_NAME = 'mysql_proxy';
const MYSQL_PROXY_IP = '127.0.0.1';
const MYSQL_PROXY_PORT = '8003';

$request_string = $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'];

\easyops\easykin\Endpoint::init(SERVICE_NAME, SERVICE_IP, SERVICE_PORT);

$traceId = !empty($_SERVER['HTTP_X_B3_TRACEID']) ? $_SERVER['HTTP_X_B3_TRACEID'] : null;
$parentSpanId = !empty($_SERVER['HTTP_X_B3_PARENTSPANID']) ? $_SERVER['HTTP_X_B3_PARENTSPANID'] : null;
$spanId = !empty($_SERVER['HTTP_X_B3_SPANID']) ? $_SERVER['HTTP_X_B3_SPANID'] : null;

$trace = new \easyops\easykin\Trace(new \easyops\easykin\ServerSpan($request_string, $traceId, $spanId, $parentSpanId));

$url = 'http://'.MYSQL_PROXY_IP.':'.MYSQL_PROXY_PORT.'/proxy.php';
$span = $trace->newSpan('get:/proxy.php', MYSQL_PROXY_NAME, MYSQL_PROXY_IP, MYSQL_PROXY_PORT);
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

echo "$request_string\n" . $request;

$logger = new SimpleHttpLogger(['host' => 'http://192.168.100.165:9411', 'muteErrors' => false]);
$logger->trace($trace->toArray());
