<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Validation
{
    protected $container;

    function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if(!isset($_SESSION['errors']))
            $_SESSION['errors'] = [];

        if(!isset($_SESSION['old']))
            $_SESSION['old'] = [];

        // errors
        $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
        unset($_SESSION['errors']);

        // postback
        $this->container->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
        $_SESSION['old'] = $request->getParams();

        $response = $next($request, $response);

        return $response;
    }
}