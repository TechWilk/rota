<?php namespace TechWilk\Rota; use DateInterval; use DateTime;
/*
    This file is part of Church Rota.

    Copyright (C) 2015 Christopher Wilkinson

  Church Rota is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Church Rota is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Church Rota.  If not, see <http://www.gnu.org/licenses/>.
*/

// Include files, including the database connection
include('includes/config.php');
include('includes/functions.php');

// Start the session. This checks whether someone is logged in and if not redirects them
session_start();

if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
    // Just continue the code
} else {
    header('Location: login.php');
    exit;
}


$action = $_GET['action'];
$userRoleId = $_GET['userrole'];
$eventId = $_GET['event'];

$userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
$eventId = filter_var($eventId, FILTER_SANITIZE_NUMBER_INT);


switch ($action) {
    case "individual":
    if ($userRoleId && $eventId) {
        echo "<p>Function not complete, please wait while we finish writing it.</p><p>Apologies for any inconvinence.</p>";
    }
        break;
    case "everyone":
      echo "<p>Function not complete, please wait while we finish writing it.</p><p>Apologies for any inconvinence.</p>";
        break;
  default:
    echo "<p>Error: Insufficient parameters.</p>";
    echo "<p>Please inform the system administrator</p>";
    break;
}
