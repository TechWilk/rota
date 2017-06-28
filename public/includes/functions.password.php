<?php
// *****************************************************************************
// File: random_password.php
// Description: PHP random password generator
// Author: Ezequiel Gastón Miravalles
// Last modification: 23/07/2009
// URL: http://www.neoegm.com/tech/online-tools/php-random-password-generator/
// *****************************************************************************

/*******************************************************************************
    Copyright (C) 2009 Ezequiel Gastón Miravalles

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*******************************************************************************/
?>
<?php 
function make_seed()    //Function make_seed from http://www.php.net/manual/en/function.srand.php
{
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}
 
function RandomPassword($length, $upper = true, $numbers = true, $lower = true)
{
    if (!$upper && !$lower && !$numbers) {
        return "";
    }
 
    $ret = "";
 
    $chars = 26 * 2 + 10;   //26 (a-z) + 26 (A-Z) + 10 (0-9)
        //a-z = 97-122
        //A-Z = 65-90
        //0-9 = 48-57
 
    srand(make_seed());     //Seed with microseconds
                            //if you don't need this, you can just use srand(time());
 
    for ($i = 1; $i <= $length; $i++) {
        $repeat = false;
 
        $num = floor(rand(0, $chars-1));
 
        if ($num < 26) {
            if ($lower) {
                $ret .= chr($num + 97);
            } else {
                $repeat = true;
            }
        } elseif ($num < 52) {
            if ($upper) {
                $ret .= chr($num - 26 + 65);
            } else {
                $repeat = true;
            }
        } elseif ($num < 62) {
            if ($numbers) {
                $ret .= chr($num - 52 + 48);
            } else {
                $repeat = true;
            }
        }
 
        if ($repeat) {
            $i--;
        }
    }
 
    return $ret;
}
?>