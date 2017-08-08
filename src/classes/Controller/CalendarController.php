<?php

namespace TechWilk\Rota\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Slim\Interfaces\RouterInterface;
use TechWilk\Rota\Authentication;
use TechWilk\Rota\CalendarTokenQuery;
use TechWilk\Rota\CalendarToken;
use TechWilk\Rota\Crypt;
use TechWilk\Rota\EventQuery;
use DateTime;
use Exception;
use InvalidArgumentException;
use Monolog\Logger;

class CalendarController
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

    public function getCalendarTokens(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch settings GET '/user/me/calendars'");

        $auth = $this->auth;
        $u = $auth->currentUser();

        if (is_null($u)) {
            return $this->view->render($response, 'error.twig');
        }

        $cals = CalendarTokenQuery::create()
            ->filterByUser($u)
            ->find();

        return $this->view->render($response, 'user-calendars.twig', [ "user" => $u, 'calendars' => $cals ]);
    }

    public function getNewCalendarForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch settings GET '/user/me/calendars'");

        $auth = $this->auth;
        $u = $auth->currentUser();

        if (is_null($u)) {
            return $this->view->render($response, 'error.twig');
        }

        $cals = CalendarTokenQuery::create()->filterByUser($u)->find();

        return $this->view->render($response, 'user-calendars.twig', [ "user" => $u, 'calendars' => $cals ]);
    }

    public function postNewCalendar(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Create calendar POST '/user/me/calendars'");

        $auth = $this->auth;
        $u = $auth->currentUser();

        if (is_null($u)) {
            return $this->view->render($response, 'error.twig');
        }

        $data = $request->getParsedBody();

        $calendar = new CalendarToken();
        $calendar->setFormat($data['format']);
        $calendar->setDescription($data['description']);
        $calendar->setUser($u);

        $crypt = new Crypt();
        $calendar->setToken($crypt->generateToken(30));

        $calendar->save();

        $cals = CalendarTokenQuery::create()->filterByUser($u)->find();

        return $this->view->render($response, 'user-calendars.twig', [ "user" => $u, 'calendars' => $cals, 'new' => $calendar ]);
    }

    public function getRevokeCalendar(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch settings GET '/user/me/calendar/".$args['id']."/revoke'");

        $auth = $this->auth;
        $u = $auth->currentUser();

        if (is_null($u)) {
            return $this->view->render($response, 'error.twig');
        }

        $c = CalendarTokenQuery::create()
            ->filterById($args['id'])
            ->findOne();

        if ($c->getUser() !== $u) {
            return $this->view->render($response, 'error.twig');
        }
        $c->setRevoked(true);
        $c->setRevokedDate(new DateTime());
        $c->save();

        return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('user-calendars'));

    }

    public function getRenderedCalendar(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch calendar GET '/calendar/".$args['token'].".".$args['format']."'");

        $c = CalendarTokenQuery::create()
            ->filterByToken($args['token'])
            ->findOne();

        if (!isset($c)) {
            return $this->view->render($response->withStatus(404), 'calendar-error.twig');
        }

        return $this->renderCalendar($c, $args['format']);
    }

    public function getLegacyRenderedCalendar(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $getParameters = $request->getQueryParams();

        $this->logger->info("Fetch -LEGACY- calendar GET '/calendar.php?user=".$getParameters['user']."&token=".$getParameters['token']."&format=".$getParameters['format']."'");

        $userId = filter_var($getParameters["user"], FILTER_VALIDATE_INT);
        $token = $getParameters["token"];
        $format = $getParameters["format"];

        $c = CalendarTokenQuery::create()
            ->filterByToken($token)
            ->filterByUserId($userId)
            ->findOne();

        if (!isset($c)) {
            return $this->view->render($response->withStatus(404), 'calendar-error.twig');
        }

        return $this->renderCalendar($c, $format);
    }

    private function renderCalendar(CalendarToken $token, $format)
    {
        $token->setLastFetched(new DateTime());
        $token->save();

        $u = $token->getUser();
        $e = EventQuery::create()
            ->useEventPersonQuery()
                ->useUserRoleQuery()
                    ->filterByUser($u)
                ->endUse()
            ->endUse()
            ->filterByRemoved(false)
            ->find();

        switch ($format) {
            case 'ical':
            case 'ics':
                return $this->view->render($response->withHeader('Content-type', 'text/calendar'), 'calendar-ical.twig', ['user' => $u, 'events' => $e]);
                break;
            default:
                return $this->view->render($response->withStatus(404), 'calendar-error.twig');
                break;
        }
    }
}
