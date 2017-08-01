<?php

namespace TechWilk\Rota;

use Slim\Views\Twig;
use TechWilk\Rota\Controller\UserController;
use TechWilk\Rota\Controller\EventController;
use Monolog;

// DIC configuration

$container = $app->getContainer();

$container['TechWilk\Rota\Controller\UserController'] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $auth = $c->get('auth');
    $router = $c->get('router');
    return new UserController($view, $logger, $auth, $router);
};

$container['TechWilk\Rota\Controller\EventController'] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $auth = $c->get('auth');
    $router = $c->get('router');
    return new EventController($view, $logger, $auth, $router);
};

$container['TechWilk\Rota\Controller\ResourceController'] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $auth = $c->get('auth');
    $router = $c->get('router');
    return new EventController($view, $logger, $auth, $router);
};
