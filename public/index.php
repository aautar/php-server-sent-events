<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

/**
 * @param string $data
 * @return string
 */
function build_sse_event_response_string(string $id, string $data): string {
    /**
     * @todo figure out what to do if there's a "\n\n" in the data string
     */
    return "id:$id\nevent: message\ndata:$data\n\n";
}

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
    // set to some reasonable value (default is 30s, if not set)
    // .. when time limit is hit, request ends and client will have to reconnect
    // .. with SSE, re-connection should happen automatically
    set_time_limit(400);

    $response = $response
        ->withBody(new \Slim\Psr7\NonBufferedBody()) // don't buffer, stream output
        ->withHeader('Content-Type', 'text/event-stream')
        ->withHeader('Cache-Control', 'no-cache');

    $body = $response->getBody();

    for($i=0; $i<100; $i++) {
        $body->write(build_sse_event_response_string(sha1($i . time()), "\$i is currently equal to: $i"));

        if(connection_aborted()) {
            break;
        }

        sleep(5);
    }

    return $response;
});

$app->run();
