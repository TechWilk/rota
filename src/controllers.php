<?php

namespace TechWilk\Rota;

use Slim\Views\Twig;
use TechWilk\Rota\Controller\UserController;
use Monolog;

// DIC configuration

$container = $app->getContainer();

$container['TechWilk\Rota\Controller\UserController'] = function($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $auth = $c->get('auth');
    return new UserController($view, $logger, $auth);
};

