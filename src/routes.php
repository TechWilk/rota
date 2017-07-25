<?php

namespace TechWilk\Rota;

use DateTime;
use InvalidArgumentException;
use Exception;
use TechWilk\Rota\Controller\UserController;

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
    $this->post('/{id}/assign', UserController::class . ':postUserAssignRoles')->setName('user-assign-post');
    $this->post('/{id}/password', UserController::class . ':postUserPasswordChange')->setName('user-password-post');
});



// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// EVENT
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/event', function () {
    $this->get('s', function ($request, $response, $args) {
        $this->logger->info("Fetch event GET '/events'");
        $events = EventQuery::create()->filterByDate(['min' => new DateTime()])->filterByRemoved(false)->orderByDate('asc')->find();

        return $this->view->render($response, 'events.twig', [ "events" => $events ]);
    })->setName('events');


    $this->get('s/type/{id}', function ($request, $response, $args) {
        $this->logger->info("Fetch event GET '/events/type/".$args['id']."'");

        $eventType = EventTypeQuery::create()->findPk($args['id']);

        $events = EventQuery::create()->filterByDate(['min' => new DateTime()])->filterByRemoved(false)->filterByEventType($eventType)->orderByDate('asc')->find();

        return $this->view->render($response, 'events.twig', [ "events" => $events ]);
    })->setName('events-eventtype');


    $this->get('s/subtype/{id}', function ($request, $response, $args) {
        $this->logger->info("Fetch event GET '/events/type/".$args['id']."'");

        $eventType = EventSubTypeQuery::create()->findPk($args['id']);

        $events = EventQuery::create()->filterByDate(['min' => new DateTime()])->filterByRemoved(false)->filterByEventSubType($eventType)->orderByDate('asc')->find();

        return $this->view->render($response, 'events.twig', [ "events" => $events ]);
    })->setName('events-eventsubtype');


    $this->post('[/{id}]', function ($request, $response, $args) {
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

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('event', [ 'id' => $e->getId() ]));
    })->setName('event-post');


    $this->get('/new', function ($request, $response, $args) {
        $this->logger->info("Fetch event GET '/event/new'");
        $l = LocationQuery::create()->orderByName()->find();
        $et = EventTypeQuery::create()->orderByName()->find();
        $est = EventSubTypeQuery::create()->orderByName()->find();

        return $this->view->render($response, 'event-edit.twig', [ "locations" => $l, "eventtypes" => $et, "eventsubtypes" => $est ]);
    })->setName('event-new');


    $this->get('/{id}', function ($request, $response, $args) {
        $this->logger->info("Fetch event GET '/event/".$args['id']."'");
        $e = EventQuery::create()->findPK($args['id']);

        if (!is_null($e)) {
            return $this->view->render($response, 'event.twig', [ "event" => $e ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    })->setName('event');


    $this->get('/{id}/edit', function ($request, $response, $args) {
        $this->logger->info("Fetch event GET '/event/".$args['id']."/edit'");
        $e = EventQuery::create()->findPK($args['id']);
        $l = LocationQuery::create()->orderByName()->find();
        $et = EventTypeQuery::create()->orderByName()->find();
        $est = EventSubTypeQuery::create()->orderByName()->find();

        if (!is_null($e)) {
            return $this->view->render($response, 'event-edit.twig', [ "event" => $e, "locations" => $l, "eventtypes" => $et, "eventsubtypes" => $est ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    })->setName('event-edit');

    $this->get('/{id}/copy', function ($request, $response, $args) {
        $this->logger->info("Fetch event GET '/event/".$args['id']."/copy'");
        $e = EventQuery::create()->findPK($args['id']);
        $l = LocationQuery::create()->orderByName()->find();
        $et = EventTypeQuery::create()->orderByName()->find();
        $est = EventSubTypeQuery::create()->orderByName()->find();

        if (!is_null($e)) {
            return $this->view->render($response, 'event-edit.twig', [ "copy" => true, "event" => $e, "locations" => $l, "eventtypes" => $et, "eventsubtypes" => $est ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    })->setName('event-copy');


    $this->get('/{id}/assign', function ($request, $response, $args) {
        $this->logger->info("Fetch event GET '/event/".$args['id']."/assign'");
        $e = EventQuery::create()->findPK($args['id']);
        $ur = UserRoleQuery::create()->find();

        if (!is_null($e)) {
            return $this->view->render($response, 'event-assign.twig', [ "event" => $e, "userroles" => $ur ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    })->setName('event-assign');


    $this->post('/{id}/assign', function ($request, $response, $args) {
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

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('event', [ 'id' => $eventId ]));
    })->setName('event-assign-post');
});



// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// RESOURCE
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->get('/resources', function ($request, $response, $args) {
    $this->logger->info("Fetch resource GET '/resources'");
    $resources = DocumentQuery::create()->orderByTitle()->find();

    return $this->view->render($response, 'resources.twig', [ "resources" => $resources ]);
})->setName('resources');


$app->post('/resource[/{id}]', function ($request, $response, $args) {
    $this->logger->info("Create resource POST '/resource'");

    $data = $request->getParsedBody();

    $data['title'] = filter_var(trim($data['title']), FILTER_SANITIZE_STRING);
    $data['description'] = filter_var(trim($data['description']), FILTER_SANITIZE_STRING);

    $d = new Document();

    if (isset($args['id'])) {
        $d = DocumentQuery::create()->findPK($args['id']);
    }

    $d->setTitle($data['title']);
    $d->setDescription($data['description']);
    $d->setLink(''); //todo: fix defaults

    if (!isset($args['id'])) {
        try {
            $files = $request->getUploadedFiles();
            $d->saveFile($files['file']);
        } catch (\Exception $e) {
            return $this->view->render($response, 'resource-edit.twig', ['resource' => $d, 'error' => $e]);
        }
    }

    $d->save();

    return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('resources'));
})->setName('resource-post');


$app->get('/resource[/new]', function ($request, $response, $args) {
    $this->logger->info("Fetch resource GET '/resource/new'");

    return $this->view->render($response, 'resource-edit.twig');
})->setName('resource-new');



$app->get('/resource/{id}/edit', function ($request, $response, $args) {
    $this->logger->info("Fetch resource GET '/resource/".$args['id']."/edit'");
    $d = DocumentQuery::create()->findPK($args['id']);

    if (!is_null($d)) {
        return $this->view->render($response, 'resource-edit.twig', [ "resource" => $d ]);
    } else {
        return $this->view->render($response, 'error.twig');
    }
})->setName('resource-edit');



$app->get('/resource/{id}', function ($request, $response, $args) {
    $this->logger->info("Fetch resource GET '/resource/".$args['id']."'");
    $resource = DocumentQuery::create()->findPk($args['id']);

    if (!is_null($resource)) {
        if (file_exists(__DIR__.'/../documents/'.$resource->getUrl())) {
            $file = __DIR__.'/../documents/'.$resource->getUrl();
        } elseif (file_exists(__DIR__.'/../documents/'.$resource->getId())) {
            $file = __DIR__.'/../documents/'.$resource->getId();
        } else {
            return $this->view->render($response, 'error.twig');
        }

        $fh = fopen($file, 'rb');

        $stream = new \Slim\Http\Stream($fh); // create a stream instance for the response body

        return $response->withHeader('Content-Type', 'application/force-download')
                        ->withHeader('Content-Type', 'application/octet-stream')
                        ->withHeader('Content-Type', 'application/download')
                        ->withHeader('Content-Description', 'File Transfer')
                        ->withHeader('Content-Transfer-Encoding', 'binary')
                        ->withHeader('Content-Disposition', 'attachment; filename="' . $resource->getUrl() . '"')
                        ->withHeader('Expires', '0')
                        ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                        ->withHeader('Pragma', 'public')
                        ->withBody($stream); // all stream contents will be sent to the response
    } else {
        return $this->view->render($response, 'error.twig');
    }
})->setName('resource');




// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// AUTH
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


$app->get('/login', function ($request, $response, $args) {
    $this->logger->info("Fetch login GET '/login'");

    if (isset($_SESSION['userId'])) {
        return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('home'));
    }
    $auth = $this['auth'];
    $resetPasswordUrl = $auth->getResetPasswordUrl();

    return $this->view->render($response->withStatus(401), 'login.twig', [ 'reset_password_url' => $resetPasswordUrl ]);
})->setName('login');


$app->post('/login', function ($request, $response, $args) {
    $this->logger->info("Login POST '/login'");

    $message = "Username or password incorrect.";

    $data = $request->getParsedBody();

    $auth = $this['auth'];
    $resetPasswordUrl = $auth->getResetPasswordUrl();

    try {
        $email = new EmailAddress($data['username']);
    } catch (InvalidArgumentException $e) {
        return $this->view->render($response->withStatus(401), 'login.twig', ['message' => $message, 'reset_password_url' => $resetPasswordUrl]);
    }
    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);

    if ($email == "" || $password == "") {
        return $this->view->render($response->withStatus(401), 'login.twig', ['message' => $message, 'reset_password_url' => $resetPasswordUrl]);
    }

    // login
    $auth = $this['auth'];
    try {
        if ($auth->loginAttempt($email, $password)) {
            if (isset($_SESSION['urlRedirect'])) {
                $url = $_SESSION['urlRedirect'];
                unset($_SESSION['urlRedirect']);
                return $response->withStatus(303)->withHeader('Location', $url);
            }
            return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('home'));
        }
    } catch (Exception $e) {
        $message = "Too many failed login attempts. Please try again in 15 minutes.";
    }
    return $this->view->render($response->withStatus(401), 'login.twig', ['username' => $email, 'message' => $message, 'reset_password_url' => $resetPasswordUrl ]);
})->setName('login-post');


$app->get('/logout', function ($request, $response, $args) {
    $this->logger->info("Fetch logout GET '/logout'");

    unset($_SESSION['userId']);

    return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('login'));
})->setName('logout');



// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// OTHER
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->get('/notification/{id}[/{referrer}]', function ($request, $response, $args) {
    // Sample log message
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
})->setName('notification');


$app->get('/user/me/calendars', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Fetch settings GET '/user/me/calendars'");

    $auth = $this['auth'];
    $u = $auth->currentUser();

    if (is_null($u)) {
        return $this->view->render($response, 'error.twig');
    }

    $cals = CalendarTokenQuery::create()
        ->filterByUser($u)
        ->find();

    return $this->view->render($response, 'user-calendars.twig', [ "user" => $u, 'calendars' => $cals ]);
})->setName('user-calendars');


$app->get('/user/me/calendar/new', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Fetch settings GET '/user/me/calendars'");

    $auth = $this['auth'];
    $u = $auth->currentUser();

    if (is_null($u)) {
        return $this->view->render($response, 'error.twig');
    }

    $cals = CalendarTokenQuery::create()->filterByUser($u)->find();

    return $this->view->render($response, 'user-calendars.twig', [ "user" => $u, 'calendars' => $cals ]);
})->setName('user-calendars');


$app->post('/user/me/calendar/new', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Create calendar POST '/user/me/calendars'");

    $auth = $this['auth'];
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
})->setName('user-calendar-new-post');


$app->get('/user/me/calendar/{id}/revoke', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Fetch settings GET '/user/me/calendar/".$args['id']."/revoke'");

    $auth = $this['auth'];
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
})->setName('user-calendar-revoke');


$app->get('/calendar/{token}.{format}', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Fetch calendar GET '/calendar/".$args['token'].".".$args['format']."'");

    $c = CalendarTokenQuery::create()
        ->filterByToken($args['token'])
        ->findOne();

    if (!isset($c)) {
        return $this->view->render($response->withStatus(404), 'calendar-error.twig');
    }
    $c->setLastFetched(new DateTime());
    $c->save();

    $u = $c->getUser();
    $e = EventQuery::create()
        ->useEventPersonQuery()
            ->useUserRoleQuery()
                ->filterByUser($u)
            ->endUse()
        ->endUse()
        ->filterByRemoved(false)
        ->find();

    switch ($args['format']) {
        case 'ical':
            return $this->view->render($response->withHeader('Content-type', 'text/calendar'), 'calendar-ical.twig', ['user' => $u, 'events' => $e]);
            break;
        case 'ics':
            return $this->view->render($response->withHeader('Content-type', 'text/calendar'), 'calendar-ical.twig', ['user' => $u, 'events' => $e]);
            break;
        default:
            return $this->view->render($response->withStatus(404), 'calendar-error.twig');
            break;
    }
})->setName('user-calendar');


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

$app->get('/calendar.php', function ($request, $response, $args) {
    // Sample log message

    $getParameters = $request->getQueryParams();

    $this->logger->info("Fetch -LEGACY- calendar GET '/calendar.php?user=".$getParameters['user']."&token=".$getParameters['token']."&format=".$getParameters['format']."'");

    $getParameters = $request->getQueryParams();

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
    $c->setLastFetched(new DateTime());
    $c->save();

    $u = $c->getUser();
    $e = EventQuery::create()
        ->useEventPersonQuery()
            ->useUserRoleQuery()
                ->filterByUser($u)
            ->endUse()
        ->endUse()
        ->filterByRemoved(false)
        ->find();

    return $this->view->render($response->withHeader('Content-type', 'text/calendar'), 'calendar-ical.twig', ['user' => $u, 'events' => $e]);
})->setName('user-calendar');
