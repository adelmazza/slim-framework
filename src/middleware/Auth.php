<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Tracy\Debugger;

class Auth
{

    protected $container;

    function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$this->container->auth->check())
        {
            $this->container->flash->addMessage('error', 'Access denied');
            return $response->withRedirect($this->container->router->pathFor('home'), 403);
        }

        $response = $next($request, $response);

        return $response;
    }
}