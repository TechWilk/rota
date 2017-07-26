<?php

namespace TechWilk\Rota\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use TechWilk\Rota\User;
use TechWilk\Rota\UserQuery;
use TechWilk\Rota\RoleQuery;
use TechWilk\Rota\EmailAddress;
use TechWilk\Rota\Authentication;
use Monolog\Logger;

class UserController
{
    protected $view;
    protected $logger;
    protected $auth;

    public function __construct(Twig $view, Logger $logger, Authentication $auth)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->auth = $auth;
    }

    public function getAllUsers(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/users'");
        $users = UserQuery::create()->orderByLastName()->orderByFirstName()->find();
        $roles = RoleQuery::create()->orderByName()->find();

        return $this->view->render($response, 'users.twig', [ "users" => $users, "roles" => $roles ]);
    }

    public function postUser(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
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
                $id = Crypt::generateInt(0, 2147483648); // largest int in db column
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
    }

    public function getCurrentUser(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/me'");
        // find user id from session
        $u = $this->auth->currentUser();

        if (!is_null($u)) {
            return $this->view->render($response, 'user.twig', [ "user" => $u ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function getNewUserForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/new'");
        return $this->view->render($response, 'user-edit.twig');
    }

    public function getUserEditForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/".$args['id']."/edit'");
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user-edit.twig', [ "user" => $u ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function getUser(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/".$args['id']."'");
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user.twig', [ "user" => $u ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function getUserWidgetOnly(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/".$args['id']."'");
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user-widget.twig', [ "user" => $u ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function getUserPasswordForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/".$args['id']."/password'");
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user-password.twig', [ "user" => $u ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function postUserPasswordChange(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
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
    }

    public function getAssignRolesForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/".$args['id']."/roles'");
        $r = RoleQuery::create()->find();
        $u = UserQuery::create()->findPK($args['id']);

        if (!is_null($u)) {
            return $this->view->render($response, 'user-roles-assign.twig', [ "user" => $u, "roles" => $r ]);
        } else {
            return $this->view->render($response, 'error.twig');
        }
    }

    public function postUserAssignRoles(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Create user people POST '/user/".$args['id']."/assign'");

        $userId = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
        $existingRoles = RoleQuery::create()->useUserRoleQuery()->filterByUserId($userId)->endUse()->find();

        $existingRoleIds = [];
        foreach ($existingRoles as $r) {
            $existingRoleIds[] = $r->getId();
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
            $addArray = array_diff($data['role'], $existingRoleIds);
            foreach ($addArray as $roleToAdd) {
                $ur = new UserRole();
                $ur->setRoleId($roleToAdd);
                $ur->setUserId($userId);
                $ur->save();
            }

            // remove existing roles
            $deleteArray = array_diff($existingRoleIds, $data['role']);
            foreach ($deleteArray as $roleToRemove) {
                $ur = UserRoleQuery::create()->filterByUserId($userId)->filterByRoleId($roleToRemove)->findOne();
                $ur->delete();
            }
        }

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('user', [ 'id' => $userId ]));

    }
}
