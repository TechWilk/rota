<?php

namespace TechWilk\Rota;

interface EmailProviderInterface
{
    public function send(Email $email);

    public function sendMultiple($emails);
}
