<?php

use Psr7Middlewares\Middleware;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

// 3) add route information to twig
$app->add(function (Request $request, Response $response, callable $next) {
    $route = $request->getAttribute('route');

    // return NotFound for non existent route
    if (empty($route)) {
        throw new NotFoundException($request, $response);
    }

    $env = $this->get('view')->getEnvironment();
    $env->addGlobal('currentroute', $route);

    return $next($request, $response);
});

// 2) Ensure user is authenticated
$app->add($app->getContainer()['auth']);

// 1) remove trailing slashes
$app->add(Middleware::TrailingSlash(false)->redirect(301));
