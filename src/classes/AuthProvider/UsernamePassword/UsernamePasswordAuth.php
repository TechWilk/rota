<?php

namespace TechWilk\Rota\AuthProvider\UsernamePassword;

use TechWilk\Rota\AuthProvider\UsernamePasswordInterface;
use TechWilk\Rota\UserQuery;
use TechWilk\Rota\LoginFailureQuery;
use TechWilk\Rota\LoginFailure;
use TechWilk\Rota\EmailAddress;
use DateTime;
use Exception;

class UsernamePasswordAuth implements UsernamePasswordInterface
{
    protected $enabled;

    public function __construct($enabled = true)
    {
        $this->enabled = (bool)$enabled;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function checkCredentials($username, $password)
    {
        $email = new EmailAddress($username);

        if (!$this->numberOfLoginAttemptsIsOk($email)) {
            throw new Exception('Too many attempts.');
            return false;
        }
        $users = UserQuery::create()->filterByEmail($email)->find();
        foreach ($users as $u) {
            if ($u->checkPassword($password)) {
                return true;
            }
        }
        $this->logFailedLoginAttempt($email);
        return false;
    }

    private function numberOfLoginAttemptsIsOk($username)
    {
        $numberOfAllowedAttempts = 8;
        $lockOutInterval = 15; // mins

        $loginFailures = LoginFailureQuery::create()
            ->filterByUsername($username)
            ->filterByTimestamp(['min' => new DateTime("-$lockOutInterval minutes")])
            ->count();

        if ($loginFailures < $numberOfAllowedAttempts) {
            return true;
        } else {
            $this->logFailedLoginAttempt($username);
            return false;
        }
    }

    private function logFailedLoginAttempt($username)
    {
        $f = new LoginFailure();
        $f->setUsername($username);
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        $f->setIpAddress($ip);
        $f->save();
    }
}
