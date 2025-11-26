<?php

use Slim\Factory\AppFactory;
use App\Middleware\AuthMiddleware;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/config/database.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$routes = require __DIR__ . '/../app/config/routes.php';
$routes($app);

$app->run();
