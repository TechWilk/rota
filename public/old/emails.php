<?php

namespace TechWilk\Rota;

/*
    This file is part of Church Rota.

    Copyright (C) 2011 David Bunce

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
include 'includes/config.php';
include 'includes/functions.php';

// Start the session. This checks whether someone is logged in and if not redirects them
session_start();

if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
    // Just continue the code
} else {
    header('Location: login.php');
    exit;
}
if (!isAdmin()) {
    header('Location: error.php?no=100&page='.basename($_SERVER['SCRIPT_FILENAME']));
    exit;
}

// Handle details from the header
$removeEventID = $_GET['eventID'];
$removeWholeEvent = $_GET['wholeEventID'];
$showmyevents = $_GET['showmyevents'];
$removeSkillID = $_GET['skillID'];
$notifyIndividual = $_GET['notifyIndividual'];
$notifyEveryone = $_GET['notifyEveryone'];
$skillremove = $_GET['skillremove'];

// Method to remove  someone from the band
if ($skillremove == 'true') {
    removeEvent($removeWholeEvent);
    removeEventPeople($removeEventID, $removeSkillID);
}

if ($notifyEveryone == 'true') {
    notifyEveryoneForEvent($removeEventID);
}

if (isset($notifyIndividual)) {
    notifyIndividual($notifyIndividual, $removeEventID, $removeSkillID);
}

// If the form has been sent, we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $editeventID = $_GET['event'];
    $editskillID = $_POST['name'];
    $editbandID = $_POST['band'];

    if ($editskillID != '') {
        $sql = ("INSERT INTO cr_eventPeople (eventID, skillID) VALUES ('$editeventID', '$editskillID')");
        if (!mysqli_query(db(), $sql)) {
            die('Error: '.mysqli_error(db()));
        }

        // After we have inserted the data, we want to head back to the main page
        header('Location: index.php');
        exit;
    }

    if ($editbandID != '') {
        $sqlbandMembers = "SELECT * FROM cr_bandMembers WHERE bandID = '$editbandID'";
        $resultbandMembers = mysqli_query(db(), $sqlbandMembers) or die(mysqli_error(db()));

        while ($bandMember = mysqli_fetch_array($resultbandMembers, MYSQLI_ASSOC)) {
            $editskillID = $bandMember['skillID'];

            $sql = ("INSERT INTO cr_eventPeople (eventID, skillID) VALUES ('$editeventID', '$editskillID')");
            if (!mysqli_query(db(), $sql)) {
                die('Error: '.mysqli_error(db()));
            }
        }

        // After we have inserted the data, we want to head back to the main page
        header('Location: index.php');
        exit;
    }
}
$formatting = 'light';
$sql = 'select * FROM cr_subscriptions';
$result = mysqli_query(db(), $sql);

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo $row['email'];
    echo '<br />';
}
