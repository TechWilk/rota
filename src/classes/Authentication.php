<?php

namespace TechWilk\Rota;

use DateTime;
use DateTimeZone;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Authentication
{
    protected $container;
    protected $authProvider;
    protected $routesWhitelist;

    public function __construct(ContainerInterface $container, AuthProviderInterface $authProvider, $routesWhitelist)
    {
        $this->container = $container;
        $this->authProvider = $authProvider;
        $this->routesWhitelist = $routesWhitelist;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        /* Skip auth if uri is whitelisted. */
        if ($this->uriInWhitelist($request)) {
            $response = $next($request, $response);

            return $response;
        }

        if ($this->isUserLoggedIn()) {
            $response = $next($request, $response);
        } else {
            $_SESSION['urlRedirect'] = strval($request->getUri());
            $router = $this->container->get('router');

            return $response->withStatus(302)->withHeader('Location', $router->pathFor('login'));
        }

        return $response;
    }

    private function uriInWhitelist(ServerRequestInterface $request)
    {
        $route = $request->getAttribute('route');
        if (!isset($route)) {
            return false;
        }

        return in_array($route->getName(), $this->routesWhitelist);
    }

    public function isUserLoggedIn()
    {
        return isset($_SESSION['userId']);
    }

    public function loginAttempt(EmailAddress $email, $password)
    {
        if (!$this->numberOfLoginAttemptsIsOk($email)) {
            throw new Exception('Too many attempts.');
            return false;
        }

        if ($this->authProvider->checkCredentials($email, $password) !== true) {
            $this->logFailedLoginAttempt($email);

            return false;
        }

        switch ($this->authProvider->getAuthProviderSlug()) {
            case 'usernamepassword':
                $user = UserQuery::create()->filterByEmail($email)->findOne();
                break;
            case 'onebody':
                if (is_null($this->authProvider->getUserId())) {
                    return false;
                }
                $socialAuth = SocialAuthQuery::create()
                    ->filterByPlatform($this->authProvider->getAuthProviderSlug())
                    ->filterBySocialId($this->authProvider->getUserId())
                    ->filterByRevoked(false)
                    ->findOne();
                if (is_null($socialAuth)) {
                    $user = UserQuery::create()->filterByEmail($email)->findOne();
                    if (!is_null($user)) {
                        $socialAuth = new SocialAuth();
                        $socialAuth->setUser($user);
                        $socialAuth->setPlatform('onebody');
                        $socialAuth->setSocialId($this->authProvider->getUserId());
                    }
                }
                if (!is_null($socialAuth)) {
                    $socialAuth->setMeta($this->authProvider->getMeta());
                    $socialAuth->save();
                    $user = $socialAuth->getUser();
                }
                break;
            case 'facebook':
                $socialAuth = SocialAuthQuery::create()
                    ->filterByPlatform($this->authProvider->getAuthProviderSlug())
                    ->filterBySocialId($this->authProvider->getUserId())
                    ->filterByRevoked(false)
                    ->findOne();
                if (!is_null($socialAuth)) {
                    //$socialAuth->setMeta($this->authProvider->getMeta());
                    //$socialAuth->save();
                    $user = $socialAuth->getUser();
                }
                break;

            default:
                $user = UserQuery::create()->filterByEmail($email)->findOne();
                break;
        }

        if (is_null($user)) {
            return false;
        }

        $_SESSION['userId'] = $user->getId();
        $user->setLastLogin(new DateTime());
        $user->save();

        return true;
    }

    private function numberOfLoginAttemptsIsOk($username)
    {
        $numberOfAllowedAttempts = 8;
        $lockOutInterval = 15; // mins

        $date = new DateTime("-$lockOutInterval minutes");
        $date->setTimezone(new DateTimeZone('UTC'));

        // check ip address
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $loginFailures = LoginFailureQuery::create()
                ->filterByIpAddress($_SERVER['REMOTE_ADDR'])
                ->filterByTimestamp(['min' => $date])
                ->count();

            if ($loginFailures >= $numberOfAllowedAttempts) {
                $this->logFailedLoginAttempt($username);

                return false;
            }
        }

        // check user account
        $loginFailures = LoginFailureQuery::create()
            ->filterByUsername($username)
            ->filterByTimestamp(['min' => $date])
            ->count();

        if ($loginFailures >= $numberOfAllowedAttempts) {
            $this->logFailedLoginAttempt($username);

            return false;
        }

        return true;
    }

    private function logFailedLoginAttempt($username)
    {
        $f = new LoginFailure();
        $f->setUsername($username);
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        $f->setIpAddress($ip);
        $f->save();
    }

    public function currentUser()
    {
        $userId = $_SESSION['userId'];

        return UserQuery::create()->findPK($userId);
    }

    public function getResetPasswordUrl()
    {
        return $this->authProvider->getResetPasswordUrl();
    }
}
