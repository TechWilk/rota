<?php

namespace TechWilk\Rota\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Slim\Interfaces\RouterInterface;
use TechWilk\Rota\EmailAddress;
use TechWilk\Rota\Authentication;
use Exception;
use InvalidArgumentException;
use Monolog\Logger;

class AuthController
{
    protected $view;
    protected $logger;
    protected $auth;
    protected $router;

    public function __construct(Twig $view, Logger $logger, Authentication $auth, RouterInterface $router)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->auth = $auth;
        $this->router = $router;
    }

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
        $resetPasswordUrl = $auth->getResetPasswordUrl();

        return $this->view->render($response->withStatus(401), 'login.twig', [ 'reset_password_url' => $resetPasswordUrl ]);
    }

    public function postLogin(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Login POST '/login'");

        $message = "Username or password incorrect.";
        $data = $request->getParsedBody();
        $auth = $this->auth;
        $resetPasswordUrl = $auth->getResetPasswordUrl();

        try {
            $email = new EmailAddress($data['username']);
        } catch (InvalidArgumentException $e) {
            return $this->view->render($response->withStatus(401), 'login.twig', ['message' => $message, 'reset_password_url' => $resetPasswordUrl]);
        }
        $password = $data['password'];

        if ($email == "" || $password == "") {
            return $this->view->render($response->withStatus(401), 'login.twig', ['message' => $message, 'reset_password_url' => $resetPasswordUrl]);
        }

        // login
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
    }

}
