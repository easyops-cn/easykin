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

const SERVICE_NAME = 'service_b';
const SERVICE_IP = '127.0.0.1';
const SERVICE_PORT = '8002';

\easyops\easykin\Endpoint::init(SERVICE_NAME, SERVICE_IP, SERVICE_PORT);

$traceId = !empty($_SERVER['HTTP_X_B3_TRACEID']) ? $_SERVER['HTTP_X_B3_TRACEID'] : null;
$parentSpanId = !empty($_SERVER['HTTP_X_B3_PARENTSPANID']) ? $_SERVER['HTTP_X_B3_PARENTSPANID'] : null;
$spanId = !empty($_SERVER['HTTP_X_B3_SPANID']) ? $_SERVER['HTTP_X_B3_SPANID'] : null;

$trace = new \easyops\easykin\Trace(new \easyops\easykin\ServerSpan('get /index.php', $traceId, $spanId, $parentSpanId));

sleep(1);

echo SERVICE_NAME.' get /index.php: <br>';

$logger = new SimpleHttpLogger(['host' => 'http://192.168.100.165:9411', 'muteErrors' => false]);
$logger->trace($trace->toArray());

