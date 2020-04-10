<?php

use Zipkin\Timestamp;
use Zipkin\Propagation\Map;

require_once '../vendor/autoload.php';
require_once __DIR__ . '/zipkin_util.php';
require_once __DIR__ . '/mysql_util.php';


$tracing = create_tracing('service1', '192.168.99.88');
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$carrier = array_map(function ($header) {
    return $header[0];
}, $request->headers->all());
/* Extracts the context from the HTTP headers */
$extractor = $tracing->getPropagation()->getExtractor(new Map());
$extractedContext = $extractor($carrier);
$tracer = $tracing->getTracer();

main();

/* Sends the trace to zipkin once the response is served */
register_shutdown_function(function () use ($tracer) {
    $tracer->flush();
});

function main()
{
    global $tracer, $extractedContext, $span;
    $span = $tracer->nextSpan($extractedContext);
    $span->start();
    $span->setKind(Zipkin\Kind\SERVER);
    $span->setName('service1-main');

    CallMySQL();

    $span->finish();
}

function CallMySQL()
{
    global $tracer, $span;
    $childSpan = $tracer->newChild($span->getContext());
    $childSpan->start();
    $childSpan->setKind(Zipkin\Kind\CLIENT);
    $childSpan->setName('servie1-callmysql');

    $conn = ConnectDB();
    $sql = "select * from user where age<30";
    QueryDB($conn, $sql);
    $childSpan->tag("sql", $sql);
    CloseDBConnection($conn);

    $childSpan->finish();
}
