<?php

namespace TechWilk\Rota\Controller;

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouterInterface;
use Slim\Views\Twig;
use TechWilk\Rota\Authentication;

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
