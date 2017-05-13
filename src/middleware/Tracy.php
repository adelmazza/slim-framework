<?php

namespace App\Middleware;

use Tracy\Debugger;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Tracy
{

    public function __invoke(Request $request, Response $response, $next)
    {
        if ($request->getParsedBody()) {
            Debugger::barDump($request->getParsedBody(), 'ParsedBody');
        }

        $response = $next($request, $response);

        return $response;
    }
}