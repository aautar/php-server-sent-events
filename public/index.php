<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response = $response->withHeader('Content-Type', 'text/html');

    $body = "<!DOCTYPE html>
        <html>
            <head>
                <script>
                    const evtSource = new EventSource('/sse');
                    evtSource.addEventListener('open', (event) => { console.log(event); });
                    evtSource.addEventListener('message', (event) => { console.log(event); });
                    evtSource.addEventListener('error', (event) => { console.log(event); });
                </script>
            </head>
            <body>[check the console]</body>
        </html>";

    $response->getBody()->write($body);
    return $response;
});

$app->get('/sse', function (Request $request, Response $response, $args) {
    set_time_limit(400);

    $response = $response
        ->withBody(new \Slim\Psr7\NonBufferedBody())
        ->withHeader('Content-Type', 'text/event-stream')
        ->withHeader('Cache-Control', 'no-cache');

    $body = $response->getBody();

    for($i=0; $i<100; $i++) {
        $body->write("event: message\n");
        $body->write("data:" . "I is current equal to: $i\n\n");

        if(connection_aborted()) {
            break;
        }

        sleep(5);
    }

    return $response;
});

$app->run();
