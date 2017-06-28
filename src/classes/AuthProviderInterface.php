<?php

namespace TechWilk\Rota;

interface AuthProviderInterface
{

    /**
    *
    *  @return bool True if auth method is enabled
    */
    public function isEnabled();
}
