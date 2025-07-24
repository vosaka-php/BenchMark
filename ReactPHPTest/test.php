<?php

// $ composer require react/http react/socket # install example using Composer
// $ php example.php # run example on command line, requires no additional web server

require __DIR__ . '/vendor/autoload.php';

$http = new React\Http\HttpServer(function (Psr\Http\Message\ServerRequestInterface $request) {
    return React\Http\Message\Response::plaintext(
        "Hello World!\n"
    );
});

$socket = new React\Socket\SocketServer('0.0.0.0:8884');
$http->listen($socket);

echo "Server running at http://127.0.0.1:8884" . PHP_EOL;