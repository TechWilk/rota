<?php

namespace TechWilk\Rota;

function legacyRouteAddUser($request, $response, $args)
{
    if (!empty($args['action']) && $args['action'] == 'edit') {
        return $response->withStatus(308)->withHeader('Location', $this->router->pathFor('user-me'));
    }

    return $response->withStatus(308)->withHeader('Location', $this->router->pathFor('user-new'));
}

$app->get('/old/addUser.php', 'legacyRouteAddUser');
$app->get('/addUser.php', 'legacyRouteAddUser');

function legacyRouteUsers($request, $response, $args)
{
    return $response->withStatus(308)->withHeader('Location', $this->router->pathFor('users'));
}

$app->get('/old/users.php', 'legacyRouteUsers');
$app->get('/users.php', 'legacyRouteUsers');


function legacyRouteCalendarTokens($request, $response, $args)
{
    return $response->withStatus(308)->withHeader('Location', $this->router->pathFor('user-calendars'));
}

$app->get('/old/calendarTokens.php', 'legacyRouteAddTokens');
$app->get('/calendarTokens.php', 'legacyRouteAddTokens');

function legacyRoutePassword($request, $response, $args)
{
    if (!empty($args['id'])) {
        return $response->withStatus(308)->withHeader('Location', $this->router->pathFor('user-password'));
    }

    return $response->withStatus(308)->withHeader('Location', $this->router->pathFor('user-me'));
}

$app->get('/old/editPassword.php', 'legacyRoutePassword');
$app->get('/editPassword.php', 'legacyRoutePassword');

function legacyRouteEvent($request, $response, $args)
{
    $eventId = (int)$args['id'];

    return $response->withStatus(308)->withHeader('Location', $this->router->pathFor('event', ['id' => $eventId]));
}

$app->get('/old/event.php', 'legacyRouteEvent');
$app->get('/event.php', 'legacyRouteEvent');

function legacyRouteLogin($request, $response, $args)
{
    return $response->withStatus(308)->withHeader('Location', $this->router->pathFor('login'));
}

$app->get('/old/login.php', 'legacyRouteLogin');
$app->get('/login.php', 'legacyRouteLogin');

function legacyRouteLogout($request, $response, $args)
{
    return $response->withStatus(308)->withHeader('Location', $this->router->pathFor('logout'));
}

$app->get('/old/logout.php', 'legacyRouteLogout');
$app->get('/logout.php', 'legacyRouteLogout');

function legacyRouteResources($request, $response, $args)
{
    return $response->withStatus(308)->withHeader('Location', $this->router->pathFor('resources'));
}

$app->get('/old/resources.php', 'legacyRouteResources');
$app->get('/resources.php', 'legacyRouteResources');
