<?php

namespace TechWilk\Rota\Controller;

use Exception;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TechWilk\Rota\EmailAddress;
use TechWilk\Rota\Exception\UnknownUserException;

class AuthController extends BaseController
{
    public function getLogout(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch logout GET '/logout'");

        unset($_SESSION['userId']);

        return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('login'));
    }

    public function getLoginForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch login GET '/login'");

        if (isset($_SESSION['userId'])) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('home'));
        }

        $auth = $this->auth;
        try {
            if ($auth->isCredential()) {
                $resetPasswordUrl = $auth->getResetPasswordUrl();

                return $this->view->render($response->withStatus(401), 'login-credentials.twig', [
                    'reset_password_url' => $resetPasswordUrl,
                ]);
            } elseif ($auth->isCallback()) {
                return $this->view->render($response->withStatus(401), 'login-callback.twig', [
                    'provider' => $auth->getAuthProviderSlug(),
                ]);
            } else {
                return $response->getBody()->write('Your authentication method is invalid. If you are the administrator, please adjust the site config.');
            }
        } catch (\PDOException $e) {
            if ($e->getCode() === '42S02') {
                return $response->getBody()->write('Your database isn\'t setup correctly. If you are the administrator, please consult the installation instructions.');
            }
        }
    }

    public function postLogin(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Login POST '/login'");

        $message = 'Username or password incorrect.';
        $data = $request->getParsedBody();
        $auth = $this->auth;
        $resetPasswordUrl = $auth->getResetPasswordUrl();

        try {
            $email = new EmailAddress($data['username']);
        } catch (InvalidArgumentException $e) {
            return $this->view->render($response->withStatus(401), 'login-credentials.twig', ['message' => $message, 'reset_password_url' => $resetPasswordUrl]);
        }
        $password = $data['password'];

        if ($email == '' || $password == '') {
            return $this->view->render($response->withStatus(401), 'login-credentials.twig', ['message' => $message, 'reset_password_url' => $resetPasswordUrl]);
        }

        // login
        try {
            if ($auth->loginAttempt($email, $password)) {
                return $this->loginSuccess($response);
            }
        } catch (Exception $e) {
            $message = 'Too many failed login attempts. Please try again in 15 minutes.';
        }

        return $this->view->render($response->withStatus(401), 'login-credentials.twig', ['username' => $email, 'message' => $message, 'reset_password_url' => $resetPasswordUrl]);
    }

    public function getLoginAuth(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Login auth GET '/login/".$args['provider']."'");

        // login
        $authUrl = $this->auth->getCallbackUrl();

        return $response->withStatus(302)->withHeader('Location', $authUrl);
    }

    public function getLoginCallback(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Login auth GET '/login/".$args['provider']."/callback'");

        // login
        try {
            if ($this->auth->verifyCallback($args)) {
                return $this->loginSuccess($response);
            }
        } catch (UnknownUserException $e) {
            // allow user to sign up
            return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('sign-up'));
        }

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('login'));
    }

    private function loginSuccess(ResponseInterface $response)
    {
        if (isset($_SESSION['urlRedirect'])) {
            $url = $_SESSION['urlRedirect'];
            unset($_SESSION['urlRedirect']);

            return $response->withStatus(303)->withHeader('Location', $url);
        }

        return $response->withStatus(303)->withHeader('Location', $this->router->pathFor('home'));
    }
}
