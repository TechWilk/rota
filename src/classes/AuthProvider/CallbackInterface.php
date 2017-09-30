<?php

namespace TechWilk\Rota\AuthProvider;

use TechWilk\Rota\AuthProviderInterface;

interface CallbackInterface extends AuthProviderInterface
{
    public function getCallbackUrl();

    public function verifyCallback($args);
}
