<?php

namespace TechWilk\Rota\AuthProvider\UsernamePassword;

use TechWilk\Rota\AuthProvider\UsernamePasswordInterface;

class UsernamePasswordAuth implements UsernamePasswordInterface
{
    public function isEnabled()
    {
        return true;
    }


    public function checkCredentials($username, $password)
    {
        if (!$this->numberOfLoginAttemptsIsOk($email)) {
            throw new Exception('Too many attempts.');
            return false;
        }
        $users = UserQuery::create()->filterByEmail($email)->find();
        foreach ($users as $u) {
            if ($u->checkPassword($password)) {
                $_SESSION['userId'] = $u->getId();
                $u->setLastLogin(new DateTime);
                $u->save();
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
