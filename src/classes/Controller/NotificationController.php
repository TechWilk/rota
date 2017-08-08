<?php

namespace TechWilk\Rota\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Slim\Interfaces\RouterInterface;
use TechWilk\Rota\Authentication;
use TechWilk\Rota\NotificationQuery;
use TechWilk\Rota\NotificationClick;
use Monolog\Logger;

class NotificationController
{
    protected $view;
    protected $logger;
    protected $auth;
    protected $router;

    public function __construct(Twig $view, Logger $logger, Authentication $auth, RouterInterface $router)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->auth = $auth;
        $this->router = $router;
    }

    public function getNotificationClick(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch settings GET '/notification/".$args['id']."'");

        $n = NotificationQuery::create()->findPk($args['id']);
        $click = new NotificationClick();
        $click->setNotification($n);
        if (isset($args['referrer'])) {
            $click->setReferer($args['referrer']);
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $click->setReferer($_SERVER['HTTP_REFERER']);
        } else {
            $click->setReferer('unknown');
        }
        $click->save();

        if ($n->getLink()) {
            if (json_decode($n->getLink())) {
                $link = json_decode($n->getLink());
                return $response->withStatus(302)->withHeader('Location', $this->router->pathFor($link['route'], $link['attributes']));
            } else {
                return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('home').$n->getLink());
            }
        }
        return $this->view->render($response, 'notification.twig', ["notification" => $n ]);
    }
}
