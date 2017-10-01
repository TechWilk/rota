<?php

namespace TechWilk\Rota\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TechWilk\Rota\Crypt;
use TechWilk\Rota\EmailAddress;
use TechWilk\Rota\RoleQuery;
use TechWilk\Rota\User;
use TechWilk\Rota\UserQuery;
use TechWilk\Rota\GroupQuery;

class UserController extends BaseController
{
    public function getAllUsers(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/users'");
        $usersAll = UserQuery::create()->orderByLastName()->orderByFirstName()->find();
        $groups = GroupQuery::create()->orderByName()->find();
        $users = [];
        foreach ($usersAll as $user) {
            if ($user->authoriser()->readableBy($this->auth->currentUser())) {
                $users[] = $user;
            }
        }

        return $this->view->render($response, 'users.twig', ['users' => $users, 'groups' => $groups]);
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

        if (isset($args['id'])) {
            // edit existing user
            $returnPath = 'user';
            $u = UserQuery::create()->findPK($args['id']);
            if (!$u->authoriser()->updatableBy($this->auth->currentUser())) {
                return $this->view->render($response, 'error.twig');
            }
        } else {
            // create new user
            $returnPath = 'user-roles';
            $newIdFound = false;
            while (!$newIdFound) {
                $id = Crypt::generateInt(0, 2147483648); // largest int in db column
                if (is_null(UserQuery::create()->findPK($id))) {
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

        $returnUrl = $this->router->pathFor($returnPath, ['id' => $u->getId()]);

        return $response
            ->withStatus(303)
            ->withHeader('Location', $returnUrl);
    }

    public function getCurrentUser(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/me'");
        // find user id from session
        $u = $this->auth->currentUser();

        if (is_null($u)) {
            return $this->view->render($response, 'error.twig');
        }

        return $this->view->render($response, 'user.twig', ['user' => $u]);
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

        if (is_null($u)) {
            return $this->view->render($response, 'error.twig');
        }
        if (!$u->authoriser()->updatableBy($this->auth->currentUser())) {
            return $this->view->render($response, 'error.twig');
        }

        return $this->view->render($response, 'user-edit.twig', ['user' => $u]);
    }

    public function getUser(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/".$args['id']."'");
        $u = UserQuery::create()->findPK($args['id']);

        if (is_null($u)) {
            return $this->view->render($response, 'error.twig');
        }
        if (!$u->authoriser()->readableBy($this->auth->currentUser())) {
            return $this->view->render($response, 'error.twig');
        }

        return $this->view->render($response, 'user.twig', ['user' => $u]);
    }

    public function getUserWidgetOnly(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/".$args['id']."'");
        $u = UserQuery::create()->findPK($args['id']);

        if (is_null($u)) {
            return $response; // no error message for ajax call
        }
        if (!$u->authoriser()->readableBy($this->auth->currentUser())) {
            return $response; // no error message for ajax call
        }

        return $this->view->render($response, 'user-widget.twig', ['user' => $u]);
    }

    public function getUserPasswordForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch user GET '/user/".$args['id']."/password'");
        $u = UserQuery::create()->findPK($args['id']);

        if (is_null($u)) {
            return $this->view->render($response, 'error.twig');
        }
        if (!$u->authoriser()->updatableBy($this->auth->currentUser())) {
            return $this->view->render($response, 'error.twig');
        }

        return $this->view->render($response, 'user-password.twig', ['user' => $u]);
    }

    public function postUserPasswordChange(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Create user POST '/user/".$args['id']."/password'");

        $data = $request->getParsedBody();

        $existing = filter_var(trim($data['existing']), FILTER_SANITIZE_STRING);
        $confirm = filter_var(trim($data['confirm']), FILTER_SANITIZE_STRING);
        $new = filter_var(trim($data['new']), FILTER_SANITIZE_STRING);

        $u = UserQuery::create()->findPK($args['id']);

        if (is_null($u)) {
            return $this->view->render($response, 'error.twig');
        }
        if (!$u->authoriser()->updatableBy($this->auth->currentUser())) {
            return $this->view->render($response, 'error.twig');
        }

        if ($new == '' || $new != $confirm) {
            $message = 'New passwords did not match.';

            return $this->view->render($response, 'user-password.twig', ['user' => $u, 'message' => $message]);
        }

        if (!$u->checkPassword($existing)) {
            $message = 'Existing password not correct.';

            return $this->view->render($response, 'user-password.twig', ['user' => $u, 'message' => $message]);
        }

        $u->setPassword($new);
        $u->save();

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('user', ['id' => $u->getId()]));
    }
}
