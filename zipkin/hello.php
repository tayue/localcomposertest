<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/zipkin_util.php';
use GuzzleHttp\Client;
use Zipkin\Propagation\DefaultSamplingFlags;
use Zipkin\Propagation\Map;
use Zipkin\Timestamp;

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);


$tracing = create_tracing('hello', '192.168.99.88');
$tracer = $tracing->getTracer();
/* Always sample traces */
$defaultSamplingFlags = DefaultSamplingFlags::createAsSampled();

main();

/* Sends the trace to zipkin once the response is served */
register_shutdown_function(function () use ($tracer) {
    //fwrite(STDOUT, "register_shutdown_function");
    $tracer->flush();
});

function main()
{
    global $span, $tracer, $defaultSamplingFlags;
    /* Creates the main span */
    $span = $tracer->newTrace($defaultSamplingFlags);
    $span->start(Timestamp\now());
    $span->setName('hello-main');
    $span->setKind(Zipkin\Kind\SERVER);

    CallService1();
    CallService2();
    echo "hello zipkin php\n";

    $span->finish();
}

function CallService1()
{
    global $tracer, $span, $tracing;
    /* Creates the span for getting the users list */
    $childSpan = $tracer->newChild($span->getContext());
    $childSpan->start();
    $childSpan->setKind(Zipkin\Kind\CLIENT);
    $childSpan->setName('hello-callservice1');

    $headers = [];

    /* Injects the context into the wire */
    $injector = $tracing->getPropagation()->getInjector(new Map());
    $injector($childSpan->getContext(), $headers);

    /* HTTP Request to the backend */
    $httpClient = new Client();
    $request = new \GuzzleHttp\Psr7\Request('POST', '192.168.99.88:9501', $headers);
    $childSpan->annotate('request_started', Timestamp\now());
    $response = $httpClient->send($request);
    echo "".$response->getBody()."\n";
    $childSpan->annotate('request_finished', Timestamp\now());
    $childSpan->finish();
}

function CallService2()
{
    global $tracer, $span, $tracing;
    /* Creates the span for getting the users list */
    $childSpan = $tracer->newChild($span->getContext());
    $childSpan->start();
    $childSpan->setKind(Zipkin\Kind\CLIENT);
    $childSpan->setName('hello-callservice2');

    $headers = [];

    /* Injects the context into the wire */
    $injector = $tracing->getPropagation()->getInjector(new Map());
    $injector($childSpan->getContext(), $headers);

    /* HTTP Request to the backend */
    $httpClient = new Client();
    $request = new \GuzzleHttp\Psr7\Request('POST', '192.168.99.88:9501', $headers);
    $childSpan->annotate('request_started', Timestamp\now());
    $response = $httpClient->send($request);
    echo "".$response->getBody()."\n";
    $childSpan->annotate('request_finished', Timestamp\now());
    $childSpan->finish();
}

