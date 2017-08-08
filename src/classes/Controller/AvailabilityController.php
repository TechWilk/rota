<?php

namespace TechWilk\Rota\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Slim\Interfaces\RouterInterface;
use TechWilk\Rota\Authentication;
use TechWilk\Rota\Availability;
use TechWilk\Rota\AvailabilityQuery;
use TechWilk\Rota\UserQuery;
use TechWilk\Rota\EventQuery;
use TechWilk\Rota\EmailAddress;
use Monolog\Logger;
use DateTime;

class AvailabilityController
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

    public function getAvailabilityForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user availability GET '/user/".$args['id']."/availability'");

        $u = UserQuery::create()->findPk($args['id']);

        if (!isset($u)) {
            return $this->view->render($response->withStatus(404), 'error.twig');
        }

        $e = EventQuery::create()
            ->filterByRemoved(false)
            ->filterByDate(['min' => new DateTime()])
            ->orderByDate('asc')
            ->find();

        return $this->view->render($response, 'user-availability.twig', ['user' => $u, 'events' => $e]);
    }

    public function postAvailability(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Update user availability POST '/user/".$args['id']."/availability'");

        $u = UserQuery::create()
            ->findPk($args['id']);

        if (!isset($u)) {
            return $this->view->render($response->withStatus(404), 'error.twig');
        }

        $data = $request->getParsedBody();

        foreach ($data['events'] as $eventId) {
            $a = AvailabilityQuery::create()
                ->filterByEventId($eventId)
                ->filterByUser($u)
                ->findOne();

            if (!isset($a)) {
                $a = new Availability();
                $a->setEventId($eventId);
                $a->setUser($u);
            }

            if (array_key_exists('eventsAvailable', $data)) {
                $a->setAvailable(in_array($eventId, $data['eventsAvailable']));
            } else {
                $a->setAvailable(false);
            }

            if (strlen($data['event'.$eventId.'comment']) > 0) {
                $a->setComment($data['event'.$eventId.'comment']);
            } else {
                $a->setComment(null);
            }

            $a->save();
        }

        return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('user', [ 'id' => $u->getId() ]));
    }
}
