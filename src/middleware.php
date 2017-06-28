<?php

use Slim\App;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use TechWilk\Rota\AuthProvider\UsernamePassword\UsernamePasswordAuth;
use TechWilk\Rota\Authentication;

// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);


// add route information to twig

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


$app->add($app->getContainer()['auth']);
