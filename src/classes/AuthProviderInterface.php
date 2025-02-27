<?php

namespace TechWilk\Rota;

interface AuthProviderInterface
{
    /**
     * @return bool True if auth method is enabled
     */
    public function isEnabled();

    /**
     * @return string Unique slug identifying the auth provider.
     */
    public function getAuthProviderSlug();
}
