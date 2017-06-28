<?php

class EmailSender
{
    private $provider;

    public function __construct(EmailProviderInterface $provider)
    {
    }
}
