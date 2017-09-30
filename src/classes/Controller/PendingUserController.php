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
use TechWilk\Rota\PendingUser;
use TechWilk\Rota\PendingUserQuery;
use Exception;

class PendingUserController extends BaseController
{
    public function getSignUpForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch sign-up GET '/signup'");

        if (isset($_SESSION['userId'])) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('home'));
        }

        $auth = $this->auth;
        $slug = $auth->getAuthProviderSlug();

        try {
            $auth->getSocialUserId();
        } catch (Exception $e) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('login'));
        }

        $existingPendingUser = PendingUserQuery::create()
            ->filterBySocialId($auth->getSocialUserId())
            ->filterBySource($slug)
            ->findOne();

        if (!is_null($existingPendingUser)) {
            $firstName = $existingPendingUser->getFirstName();

            // remove any fb auth tokens
            session_unset();

            return $this->view->render($response, 'login-sign-up-complete.twig', [
                'firstname' => $firstName,
            ]);
        }

        $firstName = '';
        $lastName = '';
        $email = '';

        switch ($slug) {
            case 'facebook':
                $meta = $auth->getMeta();

                // Split first and last names from FB
                $names = explode(' ', $meta['name'], 2);

                $firstName = $names[0];
                $lastName = $names[1];
                $email = $meta['email'];
            break;
        }

        return $this->view->render($response, 'login-sign-up.twig', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $email,
        ]);
    }

    public function postSignUp(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Submit sign-up POST '/signup'");

        if (isset($_SESSION['userId'])) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('home'));
        }

        $auth = $this->auth;
        $data = $request->getParsedBody();

        $firstName = filter_var(trim($data['firstName']), FILTER_SANITIZE_STRING);
        $lastName = filter_var(trim($data['lastName']), FILTER_SANITIZE_STRING);
        $email = new EmailAddress(trim($data['email']));

        $pendingUser = new PendingUser();
        $pendingUser->setSocialId($auth->getSocialUserId());
        $pendingUser->setFirstName($firstName);
        $pendingUser->setLastName($lastName);
        $pendingUser->setEmail($email);
        $pendingUser->setSource($auth->getAuthProviderSlug());
        $pendingUser->save();

        return $this->view->render($response, 'login-sign-up-complete.twig', [
            'firstname' => $firstName,
        ]);
    }

    public function getSignUpCancel(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        session_unset();

        return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('login'));
    }
}
