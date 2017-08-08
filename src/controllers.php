<?php

namespace TechWilk\Rota;

use Slim\Views\Twig;
use TechWilk\Rota\Controller\UserController;
use TechWilk\Rota\Controller\EventController;
use TechWilk\Rota\Controller\AuthController;
use TechWilk\Rota\Controller\AvailabilityController;
use TechWilk\Rota\Controller\NotificationController;
use TechWilk\Rota\Controller\CalendarController;
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

$container['TechWilk\Rota\Controller\AuthController'] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $auth = $c->get('auth');
    $router = $c->get('router');
    return new AuthController($view, $logger, $auth, $router);
};

$container['TechWilk\Rota\Controller\NotificationController'] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $auth = $c->get('auth');
    $router = $c->get('router');
    return new NotificationController($view, $logger, $auth, $router);
};

$container['TechWilk\Rota\Controller\CalendarController'] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $auth = $c->get('auth');
    $router = $c->get('router');
    return new CalendarController($view, $logger, $auth, $router);
};

$container['TechWilk\Rota\Controller\AvailabilityController'] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $auth = $c->get('auth');
    $router = $c->get('router');
    return new AvailabilityController($view, $logger, $auth, $router);
};
