<?php
require(__DIR__ . '/../config/database.php');
require(__DIR__ . '/../config/auth.php');
require(__DIR__ . '/../config/email.php');
require(__DIR__ . '/../config/recording.php');

function getConfig()
{
    global $config;
    return $config;
}
