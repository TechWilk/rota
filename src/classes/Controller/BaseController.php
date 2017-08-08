<?php

namespace TechWilk\Rota\Controller;

use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Slim\Interfaces\RouterInterface;
use TechWilk\Rota\Authentication;
use Monolog\Logger;

class BaseController
{
    protected $view;
    protected $logger;
    protected $auth;
    protected $router;

    public function __construct(ContainerInterface $container)
    {
        $this->setupInstanceVariables(
            $container->view,
            $container->logger,
            $container->auth,
            $container->router
        );
    }

    protected function setupInstanceVariables(Twig $view, Logger $logger, Authentication $auth, RouterInterface $router)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->auth = $auth;
        $this->router = $router;
    }
}
