<?php

namespace TechWilk\Rota\AuthProvider;

interface UsernamePasswordInterface extends AuthProviderInterface
{
    public function checkCredentials($username, $password);
}
