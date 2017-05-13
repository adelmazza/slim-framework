<?php

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../src/config.php';
$app = new \Slim\App($config);

require __DIR__ . '/../src/dependencies.php';
require __DIR__ . '/../src/middleware.php';
require __DIR__ . '/../src/routing.php';

$app->run();