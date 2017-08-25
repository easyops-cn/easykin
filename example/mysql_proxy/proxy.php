<?php
/**
 * Load composer's autoload
 */

require_once '../../vendor/autoload.php';

const SERVICE_NAME = 'mysql_proxy';
const SERVICE_IP = '127.0.0.1';
const SERVICE_PORT = '8003';

$request_string = $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'];

\easyops\easykin\Endpoint::init(SERVICE_NAME, SERVICE_IP, SERVICE_PORT);

$traceId = isset($_SERVER['HTTP_X_B3_TRACEID']) ? $_SERVER['HTTP_X_B3_TRACEID'] : null;
$parentSpanId = isset($_SERVER['HTTP_X_B3_PARENTSPANID']) ? $_SERVER['HTTP_X_B3_PARENTSPANID'] : null;
$spanId = isset($_SERVER['HTTP_X_B3_SPANID']) ? $_SERVER['HTTP_X_B3_SPANID'] : null;
$sampled = isset($_SERVER['HTTP_X_B3_SAMPLED']) ? $_SERVER['HTTP_X_B3_SAMPLED'] : null;

$trace = new \easyops\easykin\Trace($request_string, $sampled, $traceId, $parentSpanId, $spanId);
$span = $trace->newSpan('select', 'mysql', '127.0.0.1', '3306');
usleep(500);
$span->receive();

echo "$request_string\n";

$trace->trace(new easyops\easykin\logger\HttpLogger('http://192.168.100.165:9411/api/v1/spans', false));
$trace->trace(new \easyops\easykin\logger\FileLogger('.'));
