<?php

namespace TechWilk\Rota;

use DateTime;
use InvalidArgumentException;
use Exception;
use TechWilk\Rota\Controller\UserController;
use TechWilk\Rota\Controller\EventController;
use TechWilk\Rota\Controller\AuthController;
use TechWilk\Rota\Controller\NotificationController;
use TechWilk\Rota\Controller\CalendarController;

// Routes

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// USER
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/user', function () {
    $this->get('s', UserController::class . ':getAllUsers')->setName('users');

    $this->get('/{id}/widget-only', UserController::class . ':getUserWidgetOnly')->setName('user-widget-only');

    $this->get('/new', UserController::class . ':getNewUserForm')->setName('user-new');
    $this->get('/{id}/edit', UserController::class . ':getUserEditForm')->setName('user-edit');
    $this->get('/{id}/roles', UserController::class . ':getAssignRolesForm')->setName('user-roles');
    $this->get('/{id}/password', UserController::class . ':getUserPasswordForm')->setName('user-password');

    $this->get('/me', UserController::class . ':getCurrentUser')->setName('user-me');
    $this->get('/{id}', UserController::class . ':getUser')->setName('user');

    $this->post('[/{id}]', UserController::class . ':postUser')->setName('user-post');
    $this->post('/{id}/roles', UserController::class . ':postUserAssignRoles')->setName('user-assign-post');
    $this->post('/{id}/password', UserController::class . ':postUserPasswordChange')->setName('user-password-post');
});



// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// EVENT
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/event', function () {
    $this->get('s', EventController::class . ':getAllEvents')->setName('events');
    $this->get('s/type/{id}', EventController::class . ':getAllEventsWithType')->setName('events-eventtype');
    $this->get('s/subtype/{id}', EventController::class . ':getAllEventsWithSubType')->setName('events-eventsubtype');

    $this->get('s/print/preachers', EventController::class . ':getAllEventsToPrint')->setName('events-print');

    $this->get('/new', EventController::class . ':getNewEventForm')->setName('event-new');
    $this->get('/{id}/edit', EventController::class . ':getEventEditForm')->setName('event-edit');
    $this->get('/{id}/copy', EventController::class . ':getEventCopyForm')->setName('event-copy');
    $this->get('/{id}/assign', EventController::class . ':getEventAssignForm')->setName('event-assign');

    $this->get('/{id}', EventController::class . ':getEvent')->setName('event');

    $this->post('[/{id}]', EventController::class . ':postEvent')->setName('event-post');
    $this->post('/{id}/assign', EventController::class . ':postEventAssign')->setName('event-assign-post');
});



// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// RESOURCE
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/resource', function () {
    $this->get('/s', ResourceController::class . ':getAllResources')->setName('resources');

    $this->get('[/new]', ResourceController::class . ':getNewResourceForm')->setName('resource-new');
    $this->get('/{id}/edit', ResourceController::class . ':getResourceEditForm')->setName('resource-edit');

    $this->get('/{id}', ResourceController::class . ':getResourceFile')->setName('resource');

    $this->post('[/{id}]', ResourceController::class . ':postResource')->setName('resource-post');
});



// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// AUTH
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->get('/login', AuthController::class . ':getLoginForm')->setName('login');
$app->get('/logout', AuthController::class . ':getLogout')->setName('logout');

$app->post('/login', AuthController::class . ':postLogin')->setName('login-post');



// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// NOTIFICATIONS
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->get('/notification/{id}[/{referrer}]', NotificationController::class . ':getNotificationClick')->setName('notification');



// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// CALENDAR
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/user/me/calendar', function () {

    $this->get('s', CalendarController::class . ':getCalendarTokens')->setName('user-calendars');

    $this->get('/new', CalendarController::class . ':getNewCalendarForm')->setName('user-calendars');

    $this->get('/{id}/revoke', CalendarController::class . ':getRevokeCalendar')->setName('user-calendar-revoke');

    $this->post('/new', CalendarController::class . ':postNewCalendar')->setName('user-calendar-new-post');
});


$app->get('/calendar/{token}.{format}', CalendarController::class . ':getRenderedCalendar')->setName('user-calendar');



// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// OTHER
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->get('/user/{id}/availability', function ($request, $response, $args) {
    // Sample log message
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
})->setName('user-availability');


$app->post('/user/{id}/availability', function ($request, $response, $args) {
    // Sample log message
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
})->setName('user-availability-post');


$app->get('/settings', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Fetch settings GET '/settings'");

    return $this->view->render($response, 'settings.twig', [ ]);
})->setName('settings');


$app->get('/token', function ($request, $response, $args) {
    return $response->getBody()->write(Crypt::generateToken(30));
})->setName('token');


$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Fetch home GET '/'");

    $eventsThisWeek = EventQuery::create()
        ->filterByDate(['min' => new DateTime(), 'max' => new DateTime('1 week')])
        ->filterByRemoved(false)
        ->orderByDate()
        ->find();

    $remainingEventsInGroups = GroupQuery::create()->find();

    // Render index view
    return $this->view->render($response, 'home.twig', ['eventsthisweek' => $eventsThisWeek, 'remainingeventsingroups' => $remainingEventsInGroups, ]);
})->setName('home');





// LEGACY

$app->get('/calendar.php', CalendarController::class . ':getLegacyRenderedCalendar')->setName('user-calendar');
