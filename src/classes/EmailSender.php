<?php

namespace TechWilk\Rota;

// template rendering

class EmailSender
{
    private $provider;

    public function __construct(EmailProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function send(Email $email)
    {
        // save to db

        // actually send
    }

    public function renderReminderEmail()
    {
        //
    }
}
