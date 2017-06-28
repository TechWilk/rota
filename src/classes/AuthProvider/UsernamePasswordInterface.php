<?php

namespace TechWilk\Rota\AuthProvider;

use TechWilk\Rota\AuthProviderInterface;

interface UsernamePasswordInterface extends AuthProviderInterface
{
    public function checkCredentials($username, $password);
}
