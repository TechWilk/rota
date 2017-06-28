<?php

if (empty($use_session) || $use_session == true) {
    // ~~~~~ Start session ~~~~~

    session_start();

    if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
        // continue code
    } else {
        header("Location: login.php");
    }
}


// ~~~~~ Includes ~~~~~

include_once "config/database.php";
require_once "api-classes/Database.php";
require_once "api-classes/Event.php";
require_once "api-classes/Series.php";
require_once "api-classes/Sermon.php";
require_once "api-classes/Location.php";
require_once "api-classes/Type.php";
require_once "api-classes/SubType.php";

$db = new Database($config["db"]);
