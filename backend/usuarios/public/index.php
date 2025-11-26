
<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/config/database.php';

$app = AppFactory::create();

$routes = require __DIR__ . '/../app/config/routes.php';
$routes($app);   

$app->run();
