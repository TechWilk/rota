<?php

namespace TechWilk\Rota;

use DateTime;
use InvalidArgumentException;
use Exception;

// Routes

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// USER
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$app->group('/user', function () {
    $this->get('s', function ($request, $response, $args) {
        $this->logger->info("Fetch user GET '/users'");
        $users = UserQuery::create()->orderByLastName()->orderByFirstName()->find();
        $roles = RoleQuery::create()->orderByName()->find();

        return $this->view->render($response, 'users.twig', [ "users" => $users, "roles" => $roles ]);
    })->setName('users');


    $this->post('[/{id}]', function ($request, $response, $args) {
        $this->logger->info("Create user POST '/user'");

        $data = $request->getParsedBody();

        $data['firstname'] = filter_var(trim($data['firstname']), FILTER_SANITIZE_STRING);
        $data['lastname'] = filter_var(trim($data['lastname']), FILTER_SANITIZE_STRING);
        $data['email'] = new EmailAddress($data['email']);
        $data['mobile'] = filter_var(trim($data['mobile']), FILTER_SANITIZE_STRING);


        $u = new User();

        if ($args['id']) {
            $u = UserQuery::create()->findPK($args['id']);
        } else {
            $newIdFound = false;
            while (!$newIdFound) {
                $id = Crypt::generateInt(0, 2147483648);
                if (is_null(UserQuery::create()->findPK($args['id']))) {
                    $newIdFound = true;
                    $u->setId($id);
                }
            }
        }

        $u->setFirstName($data['firstname']);
        $u->setLastName($data['lastname']);

        $u->setEmail($data['email']);
        $u->setMobile($data['mobile']);

        $u->save();

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('user', [ 'id' => $u->getId() ]));
    })->setName('user-post');


    $this->get('/me', function ($request, $response, $args) {
        $this->logger->info("Fetch user GET '/user/me'");
        // find user id from session
        $auth = $this['auth'];
        $u = $auth->currentUser();

        if (!is_null($u)) {
            return $this->view->render($response, 'user.twig', [ "user" => $u ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    })->setName('user-me');


    $this->get('/new', function ($request, $response, $args) {
        $this->logger->info("Fetch user GET '/user/new'");

        return $this->view->render($response, 'user-edit.twig');
    })->setName('user-new');


    $this->get('/{id}', function ($request, $response, $args) {
        $this->logger->info("Fetch user GET '/user/".$args['id']."'");
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user.twig', [ "user" => $u ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    })->setName('user');


    $this->get('/{id}/widget-only', function ($request, $response, $args) {
        $this->logger->info("Fetch user GET '/user/".$args['id']."'");
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user-widget.twig', [ "user" => $u ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    })->setName('user-widget-only');


    $this->get('/{id}/edit', function ($request, $response, $args) {
        $this->logger->info("Fetch user GET '/user/".$args['id']."/edit'");
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user-edit.twig', [ "user" => $u ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    })->setName('user-edit');



    $this->get('/{id}/password', function ($request, $response, $args) {
        $this->logger->info("Fetch user GET '/user/".$args['id']."/password'");
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user-password.twig', [ "user" => $u ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    })->setName('user-password');


    $this->post('/{id}/password', function ($request, $response, $args) {
        $this->logger->info("Create user POST '/user/".$args['id']."/password'");

        $data = $request->getParsedBody();

        $existing = filter_var(trim($data['existing']), FILTER_SANITIZE_STRING);
        $confirm = filter_var(trim($data['confirm']), FILTER_SANITIZE_STRING);
        $new = filter_var(trim($data['new']), FILTER_SANITIZE_STRING);

        $u = UserQuery::create()->findPK($args['id']);

        if ($new == "" || $new != $confirm) {
            $message = "New passwords did not match.";
            return $this->view->render($response, 'user-password.twig', [ "user" => $u, "message" => $message ]);
        }

        if (!$u->checkPassword($existing)) {
            $message = "Existing password not correct.";
            return $this->view->render($response, 'user-password.twig', [ "user" => $u, "message" => $message ]);
        }

        $u->setPassword($new);
        $u->save();

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('user', [ 'id' => $u->getId() ]));
    })->setName('user-password-post');


    $this->get('/{id}/roles', function ($request, $response, $args) {
        $this->logger->info("Fetch user GET '/user/".$args['id']."/roles'");
        $r = RoleQuery::create()->find();
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user-roles-assign.twig', [ "user" => $u, "roles" => $r ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    })->setName('user-roles');


    $this->post('/{id}/assign', function ($request, $response, $args) {
        $this->logger->info("Create user people POST '/user/".$args['id']."/assign'");

        $userId = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
        $existingRoles = RoleQuery::create()->useUserRoleQuery()->filterByUserId($userId)->endUse()->find();

        $existing = [];
        foreach ($existingRoles as $r) {
            $existing[] = $r->getId();
        }

        $data = $request->getParsedBody();

        if (!is_array($data['role'])) {
            // delete all roles
            $urs = UserRoleQuery::create()->filterByUserId($userId)->find();
            foreach ($urs as $ur) {
                $ur->delete();
            }
        } else {
            // sanitize data from user
            foreach ($data['role'] as $key => $role) {
                $data['role'][$key] = filter_var(trim($role), FILTER_SANITIZE_NUMBER_INT);
            }

            // add new roles
            $addArray = array_diff($data['role'], $existing);
            foreach ($addArray as $roleToAdd) {
                $ur = new UserRole();
                $ur->setRoleId($roleToAdd);
                $ur->setUserId($userId);
                $ur->save();
            }

            // remove existing roles
            $deleteArray = array_diff($existing, $data['role']);
            foreach ($deleteArray as $roleToRemove) {
                $ur = UserRoleQuery::create()->filterByUserId($userId)->filterByRoleId($roleToRemove)->findOne();
                $ur->delete();
            }
        }

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('user', [ 'id' => $userId ]));
    })->setName('user-assign-post');
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
        if (file_exists(__DIR__.'/../public/documents/'.$resource->getUrl())) {
            $file = __DIR__.'/../public/documents/'.$resource->getUrl(); // todo : move documents outside of web root
        } elseif (file_exists(__DIR__.'/../public/documents/'.$resource->getId())) {
            $file = __DIR__.'/../public/documents/'.$resource->getId(); // todo : move documents outside of web root
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

    $cals = CalendarTokenQuery::create()->filterByUser($u)->find();

    return $this->view->render($response, 'user-calendars.twig', [ "user" => $u, 'calendars' => $cals ]);
})->setName('user-calendars');


$app->get('/user/me/calendar/{id}/revoke', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Fetch settings GET '/user/me/calendar/".$args['id']."/revoke'");

    $auth = $this['auth'];
    $u = $auth->currentUser();

    if (is_null($u)) {
        return $this->view->render($response, 'error.twig');
    }

    $c = CalendarTokenQuery::create()->filterById($args['id'])->findOne();

    if ($c->getUser() !== $u) {
        return $this->view->render($response, 'error.twig');
    }
    $c->setRevoked(true);
    $c->save();

    return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('user-calendars'));
})->setName('user-calendar-revoke');


$app->get('/calendar/{token}.ical', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Fetch settings GET '/calendar/".$args['token'].".ical'");

    $c = CalendarTokenQuery::create()->filterByToken($args['token'])->findOne();

    if (!isset($c)) {
        return $this->view->render($response->withStatus(404), 'calendar-error.twig');
    }
    $u = $c->getUser();
    $e = EventQuery::create()->filterByUser($u)->find();

    return $this->view->render($response, 'calendar-ical.twig', ['user' => $u, 'events' => $e]);
})->setName('user-calendar');


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

    $eventsThisWeek = EventQuery::create()->filterByDate(['min' => new DateTime(), 'max' => new DateTime('1 week')])->filterByRemoved(false)->orderByDate()->find();

    $remainingEventsInGroups = GroupQuery::create()->find();

    // Render index view
    return $this->view->render($response, 'home.twig', ['eventsthisweek' => $eventsThisWeek, 'remainingeventsingroups' => $remainingEventsInGroups, ]);
})->setName('home');
