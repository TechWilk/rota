<?php

namespace TechWilk\Rota\Controller;

use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TechWilk\Rota\Event;
use TechWilk\Rota\EventPerson;
use TechWilk\Rota\EventPersonQuery;
use TechWilk\Rota\EventQuery;
use TechWilk\Rota\EventSubTypeQuery;
use TechWilk\Rota\EventTypeQuery;
use TechWilk\Rota\GroupQuery;
use TechWilk\Rota\LocationQuery;
use TechWilk\Rota\UserQuery;
use TechWilk\Rota\UserRoleQuery;

class EventController extends BaseController
{
    public function getAllEvents(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event GET '/events'");
        $events = EventQuery::create()->filterByDate(['min' => new DateTime()])->filterByRemoved(false)->orderByDate('asc')->find();

        return $this->view->render($response, 'events.twig', ['events' => $events]);
    }

    public function getAllEventsWithType(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event GET '/events/type/".$args['id']."'");

        $eventType = EventTypeQuery::create()->findPk($args['id']);

        $events = EventQuery::create()->filterByDate(['min' => new DateTime()])->filterByRemoved(false)->filterByEventType($eventType)->orderByDate('asc')->find();

        return $this->view->render($response, 'events.twig', ['events' => $events]);
    }

    public function getAllEventsWithSubType(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event GET '/events/type/".$args['id']."'");

        $eventType = EventSubTypeQuery::create()->findPk($args['id']);

        $events = EventQuery::create()->filterByDate(['min' => new DateTime()])->filterByRemoved(false)->filterByEventSubType($eventType)->orderByDate('asc')->find();

        return $this->view->render($response, 'events.twig', ['events' => $events]);
    }

    public function postEvent(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Create event POST '/event'");

        $data = $request->getParsedBody();

        $data['firstname'] = filter_var(trim($data['firstname']), FILTER_SANITIZE_STRING);
        $data['lastname'] = filter_var(trim($data['lastname']), FILTER_SANITIZE_STRING);
        $data['email'] = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $data['mobile'] = filter_var(trim($data['mobile']), FILTER_SANITIZE_STRING);

        $e = new Event();
        if (isset($args['id'])) {
            $e = EventQuery::create()->findPk($args['id']);
        }
        $e->setName($data['name']);
        $e->setDate(DateTime::createFromFormat('d/m/Y H:i', $data['date'].' '.$data['time']));
        $e->setEventTypeId($data['type']);
        $e->setEventSubTypeId($data['subtype']);
        $e->setLocationId($data['location']);
        $e->setComment($data['comment']);
        $e->save();

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('event', ['id' => $e->getId()]));
    }

    public function getNewEventForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event GET '/event/new'");
        $l = LocationQuery::create()->orderByName()->find();
        $et = EventTypeQuery::create()->orderByName()->find();
        $est = EventSubTypeQuery::create()->orderByName()->find();

        return $this->view->render($response, 'event-edit.twig', ['locations' => $l, 'eventtypes' => $et, 'eventsubtypes' => $est]);
    }

    public function getEvent(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event GET '/event/".$args['id']."'");
        $e = EventQuery::create()->findPK($args['id']);

        if (!is_null($e)) {
            return $this->view->render($response, 'event.twig', ['event' => $e]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function getEventEditForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event GET '/event/".$args['id']."/edit'");
        $e = EventQuery::create()->findPK($args['id']);
        $l = LocationQuery::create()->orderByName()->find();
        $et = EventTypeQuery::create()->orderByName()->find();
        $est = EventSubTypeQuery::create()->orderByName()->find();

        if (!is_null($e)) {
            return $this->view->render($response, 'event-edit.twig', ['event' => $e, 'locations' => $l, 'eventtypes' => $et, 'eventsubtypes' => $est]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function getEventCopyForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event GET '/event/".$args['id']."/copy'");
        $e = EventQuery::create()->findPK($args['id']);
        $l = LocationQuery::create()->orderByName()->find();
        $et = EventTypeQuery::create()->orderByName()->find();
        $est = EventSubTypeQuery::create()->orderByName()->find();

        if (!is_null($e)) {
            return $this->view->render($response, 'event-edit.twig', ['copy' => true, 'event' => $e, 'locations' => $l, 'eventtypes' => $et, 'eventsubtypes' => $est]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function getEventAssignForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event GET '/event/".$args['id']."/assign'");
        $e = EventQuery::create()->findPK($args['id']);
        $ur = UserRoleQuery::create()->find();

        if (!is_null($e)) {
            return $this->view->render($response, 'event-assign.twig', ['event' => $e, 'userroles' => $ur]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function postEventAssign(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Create event people POST '/event".$args['id']."/assign'");

        $eventId = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
        $existingUserRoles = UserRoleQuery::create()->useEventPersonQuery()->filterByEventId($eventId)->endUse()->find();

        $existing = [];
        foreach ($existingUserRoles as $ur) {
            $existing[] = $ur->getId();
        }

        $data = $request->getParsedBody();

        if (!is_array($data['userrole'])) {
            // delete all roles
            $eps = EventPersonQuery::create()->filterByEventId($eventId)->find();
            foreach ($eps as $ep) {
                $ep->delete();
            }
        } else {
            // sanitize data from user
            foreach ($data['userrole'] as $key => $userRole) {
                $data['userrole'][$key] = filter_var(trim($userRole), FILTER_SANITIZE_NUMBER_INT);
            }

            // add new roles
            $addArray = array_diff($data['userrole'], $existing);
            foreach ($addArray as $roleToAdd) {
                $ep = new EventPerson();
                $ep->setUserRoleId($roleToAdd);
                $ep->setEventId($eventId);
                $ep->save();
            }

            // remove existing roles
            $deleteArray = array_diff($existing, $data['userrole']);
            foreach ($deleteArray as $roleToRemove) {
                $ep = EventPersonQuery::create()->filterByEventId($eventId)->filterByUserRoleId($roleToRemove)->findOne();
                $ep->delete();
            }
        }

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('event', ['id' => $eventId]));
    }

    public function getAllEventsToPrint(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event printable page GET '/events/print'");

        $events = EventQuery::create()
            ->filterByDate(['min' => new DateTime()])
            ->filterByRemoved(false)
            ->orderByDate('asc')
            ->find();

        $groups = GroupQuery::create()
            ->filterById(136)
            ->find();

        $users = UserQuery::create()
            ->useUserRoleQuery()
                ->filterByReserve(false)
                ->useRoleQuery()
                    ->filterByGroup($groups)
                ->endUse()
            ->endUse()
            ->distinct()
            ->find();

        return $this->view->render($response, 'events-print.twig', ['events' => $events, 'groups' => $groups, 'users' => $users]);
    }
}
