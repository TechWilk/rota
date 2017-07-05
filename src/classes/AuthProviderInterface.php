<?php

namespace TechWilk\Rota;

interface AuthProviderInterface
{

    /**
    *
    *  @return bool True if auth method is enabled
    */
    public function isEnabled();

    /**
    *
    *  @return string|null URL path to reset password (with full domain if required). Null is returned if user is unable to reset password, or the URL cannot be determined by the auth provider.
    */
    public function getResetPasswordUrl();
}
