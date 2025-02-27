<?php

namespace TechWilk\Rota\AuthProvider;

use TechWilk\Rota\AuthProviderInterface;

interface UsernamePasswordInterface extends AuthProviderInterface
{
    public function checkCredentials($username, $password);

    /**
     * @return string|null URL path to reset password (with full domain if required). Null is returned if user is unable to reset password, or the URL cannot be determined by the auth provider.
     */
    public function getResetPasswordUrl();
}
