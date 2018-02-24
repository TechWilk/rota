<?php

namespace TechWilk\Rota;

use DateTime;
use TechWilk\Rota\Controller\AuthController;
use TechWilk\Rota\Controller\AvailabilityController;
use TechWilk\Rota\Controller\CalendarController;
use TechWilk\Rota\Controller\EventController;
use TechWilk\Rota\Controller\GroupController;
use TechWilk\Rota\Controller\InstallController;
use TechWilk\Rota\Controller\JobController;
use TechWilk\Rota\Controller\NotificationController;
use TechWilk\Rota\Controller\PendingUserController;
use TechWilk\Rota\Controller\ResourceController;
use TechWilk\Rota\Controller\RoleController;
use TechWilk\Rota\Controller\UserController;

// Routes

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// USER
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/user', function () {
    $this->get('s', UserController::class.':getAllUsers')->setName('users');

    $this->get('/{id}/widget-only', UserController::class.':getUserWidgetOnly')->setName('user-widget-only');

    $this->get('/new', UserController::class.':getNewUserForm')->setName('user-new');
    $this->get('/{id}/edit', UserController::class.':getUserEditForm')->setName('user-edit');
    $this->get('/{id}/password', UserController::class.':getUserPasswordForm')->setName('user-password');

    $this->get('/me', UserController::class.':getCurrentUser')->setName('user-me');
    $this->get('/{id}', UserController::class.':getUser')->setName('user');

    $this->post('[/{id}]', UserController::class.':postUser')->setName('user-post');
    $this->post('/{id}/password', UserController::class.':postUserPasswordChange')->setName('user-password-post');

    // roles
    $this->get('/{id}/roles', RoleController::class.':getAssignRolesForm')->setName('user-roles');
    $this->post('/{id}/roles', RoleController::class.':postUserAssignRoles')->setName('user-assign-post');

    // availability
    $this->get('/{id}/availability', AvailabilityController::class.':getAvailabilityForm')->setName('user-availability');
    $this->post('/{id}/availability', AvailabilityController::class.':postAvailability')->setName('user-availability-post');

    // calendar
    $this->group('/me/calendar', function () {
        $this->get('s', CalendarController::class.':getCalendarTokens')->setName('user-calendars');
        $this->get('/new', CalendarController::class.':getNewCalendarForm')->setName('user-calendars');
        $this->get('/{id}/revoke', CalendarController::class.':getRevokeCalendar')->setName('user-calendar-revoke');
        $this->post('/new', CalendarController::class.':postNewCalendar')->setName('user-calendar-new-post');
    });
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// EVENT
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/event', function () {
    $this->get('s', EventController::class.':getAllEvents')->setName('events');
    $this->get('s/type/{id}', EventController::class.':getAllEventsWithType')->setName('events-eventtype');
    $this->get('s/subtype/{id}', EventController::class.':getAllEventsWithSubType')->setName('events-eventsubtype');

    $this->get('s/print/info', EventController::class.':getAllEventInfoToPrint')->setName('events-print-info');

    $this->get('/new', EventController::class.':getNewEventForm')->setName('event-new');
    $this->get('/{id}/edit', EventController::class.':getEventEditForm')->setName('event-edit');
    $this->get('/{id}/copy', EventController::class.':getEventCopyForm')->setName('event-copy');
    $this->get('/{id}/assign', EventController::class.':getEventAssignForm')->setName('event-assign');

    $this->get('/{id}', EventController::class.':getEvent')->setName('event');

    $this->post('[/{id}]', EventController::class.':postEvent')->setName('event-post');
    $this->post('/{id}/assign', EventController::class.':postEventAssign')->setName('event-assign-post');
    $this->post('/{id}/comment', EventController::class.':postEventComment')->setName('event-comment-post');
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// RESOURCE
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/resource', function () {
    $this->get('s', ResourceController::class.':getAllResources')->setName('resources');

    $this->get('[/new]', ResourceController::class.':getNewResourceForm')->setName('resource-new');
    $this->get('/{id}/edit', ResourceController::class.':getResourceEditForm')->setName('resource-edit');

    $this->get('/{id}', ResourceController::class.':getResourceFile')->setName('resource');

    $this->post('[/{id}]', ResourceController::class.':postResource')->setName('resource-post');
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ROLES & GROUPS
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/group', function () {
    $this->get('/{id}', GroupController::class.':getGroup')->setName('group');
    $this->get('/{id}/roles', GroupController::class.':getGroupRoles')->setName('group-roles');

    $this->get('/{id}/events', EventController::class.':getAllEventsToPrintForGroup')->setName('group-events-print');

    $this->post('[/{id}]', GroupController::class.':postGroup')->setName('group-post');
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// AUTH
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->get('/login', AuthController::class.':getLoginForm')->setName('login');

$app->get('/login/{provider}', AuthController::class.':getLoginAuth')->setName('login-auth');
$app->get('/login/{provider}/callback', AuthController::class.':getLoginCallback')->setName('login-callback');
$app->get('/signup', PendingUserController::class.':getSignUpForm')->setName('sign-up');
$app->get('/signup/cancel', PendingUserController::class.':getSignUpCancel')->setName('sign-up-cancel');

$app->get('/logout', AuthController::class.':getLogout')->setName('logout');

$app->post('/login', AuthController::class.':postLogin')->setName('login-post');
$app->post('/signup', PendingUserController::class.':postSignUp')->setName('sign-up-post');

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// NOTIFICATIONS
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->get('/notification/{id}[/{referrer}]', NotificationController::class.':getNotificationClick')->setName('notification');

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// CALENDAR
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->get('/calendar/{token}.{format}', CalendarController::class.':getRenderedCalendar')->setName('user-calendar');

// legacy
$app->get('/calendar.php', CalendarController::class.':getLegacyRenderedCalendar')->setName('user-calendar');

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// OTHER
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->get('/settings', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Fetch settings GET '/settings'");

    $url = $this->router->pathFor('home').'old/settings.php';

    return $response->withStatus(303)->withHeader('Location', $url);
    //return $this->view->render($response, 'settings.twig', []);
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
    return $this->view->render($response, 'home.twig', ['eventsthisweek' => $eventsThisWeek, 'remainingeventsingroups' => $remainingEventsInGroups]);
})->setName('home');

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// INSTALL
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/install', function () {
    $this->get('', InstallController::class.':getInstall')->setName('install');

    $this->get('/database', InstallController::class.':getInstallDatabase')->setName('install-database');

    $this->get('/user', InstallController::class.':getFirstUserForm')->setName('install-user');
    $this->post('/user', InstallController::class.':postFirstUserForm')->setName('install-user-post');
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// JOBS
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/job', function () {
    $this->get('/daily/{token}', JobController::class.':getDaily')->setName('job-daily');
});

require_once __DIR__ . '/routes-legacy.php';
