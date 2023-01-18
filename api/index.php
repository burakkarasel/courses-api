<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once realpath( __DIR__ . '/../vendor/autoload.php');
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config/db.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('<a href="/hello/world">Try /hello/world</a>');
    return $response;
});

// courses routes

require __DIR__ . "/../src/routes/courses.php";

$app->run();
