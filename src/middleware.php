<?php

$app->add(new App\Middleware\Tracy);
$app->add(new App\Middleware\Validation($container));

// session middleware must be the last of the chain
$app->add(new App\Middleware\Session($config['settings']['session']));