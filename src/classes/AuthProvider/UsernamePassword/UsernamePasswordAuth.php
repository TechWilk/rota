<?php

namespace TechWilk\Rota\AuthProvider\UsernamePassword;

use TechWilk\Rota\AuthProvider\UsernamePasswordInterface;
use TechWilk\Rota\EmailAddress;
use TechWilk\Rota\UserQuery;

class UsernamePasswordAuth implements UsernamePasswordInterface
{
    protected $enabled;

    public function __construct($enabled = true)
    {
        $this->enabled = (bool) $enabled;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function checkCredentials($username, $password)
    {
        $email = new EmailAddress($username);

        $users = UserQuery::create()->filterByEmail($email)->find();
        foreach ($users as $u) {
            if ($u->checkPassword($password)) {
                return true;
            }
        }

        return false;
    }

    public function getResetPasswordUrl()
    {
    }

    public function getAuthProviderSlug()
    {
        return 'usernamepassword';
    }
}
