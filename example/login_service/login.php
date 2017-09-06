<?php
/**
 * Load composer's autoload
 */
require_once '../../vendor/autoload.php';

const SERVICE_NAME = 'login_service';
const SERVICE_IP = '127.0.0.1';
const SERVICE_PORT = '8001';
const MYSQL_PROXY_NAME = 'mysql_proxy';
const MYSQL_PROXY_IP = '127.0.0.1';
const MYSQL_PROXY_PORT = '8003';

$request_string = $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'];

\easyops\easykin\core\Endpoint::init(SERVICE_NAME, SERVICE_IP, SERVICE_PORT);

$traceId = isset($_SERVER['HTTP_X_B3_TRACEID']) ? $_SERVER['HTTP_X_B3_TRACEID'] : null;
$parentSpanId = isset($_SERVER['HTTP_X_B3_PARENTSPANID']) ? $_SERVER['HTTP_X_B3_PARENTSPANID'] : null;
$spanId = isset($_SERVER['HTTP_X_B3_SPANID']) ? $_SERVER['HTTP_X_B3_SPANID'] : null;
$sampled = isset($_SERVER['HTTP_X_B3_SAMPLED']) ? $_SERVER['HTTP_X_B3_SAMPLED'] : null;

$trace = new \easyops\easykin\core\Trace($request_string, $sampled, $traceId, $parentSpanId, $spanId);

$url = 'http://'.MYSQL_PROXY_IP.':'.MYSQL_PROXY_PORT.'/proxy.php';
$span = $trace->newSpan('get:/proxy.php', MYSQL_PROXY_NAME, MYSQL_PROXY_IP, MYSQL_PROXY_PORT);
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' =>
            'X-B3-TraceId: ' . $span->traceId . "\r\n" .
            'X-B3-SpanId: ' . $span->id . "\r\n" .
            'X-B3-ParentSpanId: ' . $span->parentId . "\r\n" .
            'X-B3-Sampled: ' . $trace->sampled . "\r\n"
    ]
]);
$request = file_get_contents($url, false, $context);
$span->receive();

echo "$request_string\n" . $request;

$trace->trace(new easyops\easykin\logger\HttpLogger('http://192.168.100.165:9411/api/v1/spans', false));
