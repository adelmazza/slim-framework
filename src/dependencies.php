<?php

use Tracy\Debugger;
use Valitron\Validator as V;

$container = $app->getContainer();

// validation language settings
V::langDir(__DIR__.'/../vendor/vlucas/valitron/lang');
V::lang($container['settings']['validation']['lang']);

// Eloquent init
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Error Handler init
// unsetting slim's handler and enabling Tracy
unset($container['errorHandler']);
if($container['settings']['debug']) {
    Debugger::enable(Debugger::DEVELOPMENT, __DIR__ . '/../temp/log');
} else {
    // remember to delete cached files (temp/cache) !
    Debugger::enable(Debugger::PRODUCTION, __DIR__ . '/../temp/log');
}

// DI: injecting Eloquent/Capsule db management inside the container
$container['db'] = function () use ($capsule) {
    return $capsule;
};

// DI: injecting session manager
$container['session'] = function () {
    return new \App\Objects\Session;
};

// DI: injecting auth - attached on routes
$container['auth'] = function ($container) {
    return new \App\Objects\Auth($container);
};

// DI: injecting flash messages
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// DI: injecting twig templating system
$container['view'] = function ($container) {
    $cfg = $container->get('settings');

    $view = new \Slim\Views\Twig(__DIR__ . '/../views', [
        'cache' => __DIR__ . '/../temp/cache/twig',
        'debug' => $cfg['debug'],
    ]);

    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $container->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    //adding auth and flash messages as global variable available in templates
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};

// DI: injecting swift mailer
$container['swiftmailer'] = function($container) {
    $cfg = $container->get('settings');

    $transport = Swift_SmtpTransport::newInstance($cfg['mailer']['smtp'], $cfg['mailer']['port'], $cfg['mailer']['security'])
        ->setUsername($cfg['mailer']['username'])
        ->setPassword($cfg['mailer']['password']);

    $mailer = Swift_Mailer::newInstance($transport);

    return $mailer;
};

// DI: injecting Monolog
$container['logger'] = function($container) {
    $cfg = $container->get('settings');

    $logger = new Monolog\Logger($cfg['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\RotatingFileHandler(__DIR__ . '/../temp/log/' . $cfg['logger']['filename'], $cfg['logger']['maxfiles'], Monolog\Logger::DEBUG));

    return $logger;
};

// DI: injecting Validator
$container['validator'] = function ($container) {
    $request = $container->get('request')->getParsedBody();
    return new Valitron\Validator($request);
};

/*
$container['App\Controllers\Home'] = function ($container) {
    return new \App\Controllers\Home($container);
};
*/