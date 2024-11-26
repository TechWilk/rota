<?php

namespace TechWilk\Rota;

include __DIR__.'/../../../config/database.php';
include __DIR__.'/../../../config/auth.php';
include __DIR__.'/../../../config/email.php';
include __DIR__.'/../../../config/recording.php';

// -- Setup database --

//generate masked password
$pwdMasked = '';
$len = strlen($config['db']['pass']);
for ($i = 0; $i < $len; $i++) {
    $pwdMasked .= '*';
}

// Connect to the database server
$dbh = @mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['pass']) or exit('Connection to '.$config['db']['host']." with login '".$config['db']['user']."'/'$pwdMasked' failed.");

// Choose the right database
$db = @mysqli_select_db($dbh, $config['db']['dbname']) or exit("Connection made, but database '".$config['db']['dbname']."' was not found.");

// allow config to be fetched in functions

function siteConfig()
{
    global $config;

    return $config;
}

function db()
{
    global $dbh;

    return $dbh;
}
