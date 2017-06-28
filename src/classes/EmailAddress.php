<?php

namespace TechWilk\Rota;

use InvalidArgumentException;

class EmailAddress
{
    private $email;

    public function __construct($email)
    {
        if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
            $this->email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        } else {
            throw new InvalidArgumentException('Invalid email address: ' . $email);
        }
    }

    public function __toString()
    {
        return $this->email;
    }
}
