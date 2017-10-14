<?php

namespace TechWilk\Rota\EmailProvider;

use Mailgun\Mailgun as MailgunClass;
use TechWilk\Rota\Settings;

class Mailgun
{
    private $mailgun;
    private $settings;

    public function __construct(MailgunClass $mailgun, Settings $settings)
    {
        $this->mailgun = $mailgun;
        $this->settings = $settings;
    }

    public function send()
    {
    }
}
