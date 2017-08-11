<?php

namespace TechWilk\Rota;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Authorisation
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {

    // do something!

    return $response;
    }

    private function uriInWhitelist(ServerRequestInterface $request)
    {
        $route = $request->getAttribute('route');

        return in_array($route->getName(), $this->routesWhitelist);
    }

    public function isUserLoggedIn()
    {
        return isset($_SESSION['userId']);
    }
}
