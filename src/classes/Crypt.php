<?php

namespace TechWilk\Rota;

use InvalidArgumentException;

class Crypt
{
    public static function generateToken($length)
    {
        $token = '';
        for ($i=0; $i < $length; $i++) {
            $l = Crypt::generateInt(0, 63);
            if ($l < 10) { // number
        $token .= $l;
            } elseif ($l < 10 + 26) { // lowercase
        $lowercase = range('a', 'z');
                $token .= $lowercase[$l - 10];
            } elseif ($l < 10 + 26 + 26) { // uppercase
        $uppercase = range('A', 'Z');
                $token .= $uppercase[$l - 10 - 26];
            } elseif ($l < 10 + 26 + 26 + 1) { // underscore
        $token .= '_';
            } else { // hyphen
        $token .= '-';
            }
        }
        return $token;
    }

    public static function generateInt($min, $max)
    {
        if ($min >= $max) {
            throw new InvalidArgumentException('Invalid constraints - minimum ('.$min.') is above the maximum ('.$max.')');
        }

        if (function_exists('random_int')) { // use secure random numbers when available
        return random_int($min, $max);
        } else { // fallback for < PHP 7.0
        return mt_rand($min, $max);
        }
    }
}
