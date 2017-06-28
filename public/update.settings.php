<?php
/*
    This file is part of Church Rota.

    Copyright (C) 2013 Benjamin Schmitt

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
if (!isAdmin()) {
    header('Location: error.php?no=100&page='.basename($_SERVER['SCRIPT_FILENAME']));
    exit;
}

$action = $_GET['action'];
$eventID = $_GET['id'];

function detectBrowserLanguage()
{
    $langcode = explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $langcode = explode(",", $langcode['0']);
    return $langcode['0'];
}

$language = detectBrowserLanguage();

$sqlSettings = "select * from cr_settings";
$resultSettings = mysqli_query(db(), $sqlSettings) or die(mysqli_error(db()));
$rowSettings = mysqli_fetch_array($resultSettings, MYSQLI_ASSOC);

if ($action == "update") {
    //if ($language='de-de')
        if ($rowSettings['lang_locale']=="en_GB") {
            executeDbSql("update cr_settings set lang_locale = 'de_DE'");                     // de_DE
            executeDbSql("update cr_settings set time_format_long = '%A, %e. %B %Y, %R Uhr, KW%V'"); // de_DE: %A, %e. %B %Y, %R Uhr, KW%V
            executeDbSql("update cr_settings set time_format_normal = '%d.%m.%Y %H:%M '"); // de_DE: %d.%m.%Y %H:%M
            executeDbSql("update cr_settings set time_format_short = '%a, <strong>%e. %b</strong>, %R'");              // de_DE: %a, <strong>%e. %b</strong>, KW%V
            executeDbSql("update cr_settings set time_zone = 'Europe/Berlin'"); //de_DE: Europe/Berlin
            executeDbSql("update cr_settings set google_group_calendar = ''");
            executeDbSql("update cr_settings set overviewemail = '{{Gottesdienst-Planung [MONTH] [YEAR]}}\r\nHallo zusammen,\r\n\r\nanbei die Gottesdienst-Planung fuer [MONTH] [YEAR]\r\n\r\n[OVERVIEW]\r\n\r\nBitte fruehzeitig Bescheid geben, wenn etwas NICHT passt, ansonsten gehe ich davon aus, dass ihr wie geplant koennt.\r\n\r\nAlles Gute und Gottes Segen f�r Euch und Euren Dienst.\r\nEuer Gottesdienst Orga-Team'");
        }
        //else
        if ($rowSettings['lang_locale']=="de_DE") {
            executeDbSql("update cr_settings set lang_locale = 'en_GB'");                     // de_DE
            executeDbSql("update cr_settings set time_format_long = '%A, %B %e @ %I:%M %p'"); // de_DE: %A, %e. %B %Y, %R Uhr, KW%V
            executeDbSql("update cr_settings set time_format_normal = '%m/%d/%y %I:%M %p'"); // de_DE: %d.%m.%Y %H:%M
            executeDbSql("update cr_settings set time_format_short = '%a, <strong>%b %e</strong>, %I:%M %p'");              // de_DE: %a, <strong>%e. %b</strong>, KW%V
            executeDbSql("update cr_settings set time_zone = 'Europe/London'"); //de_DE: Europe/Berlin
            executeDbSql("update cr_settings set google_group_calendar = ''");
            executeDbSql("update cr_settings set overviewemail = 'Hello,\r\n\r\nIn this email you find the Rota for [MONTH] [YEAR].\r\n\r\n[OVERVIEW]\r\n\r\nPlease inform us as soon as possible, if you are not able to serve as scheduled.\r\n\r\nBe blessed.\r\nChurch Support Stuff'");
        }

        //notifyInfo(__FILE__,"settings updated",$_SESSION['userid']);
        
        $sqlSettings = "select * from cr_settings";
    $resultSettings = mysqli_query(db(), $sqlSettings) or die(mysqli_error(db()));
    $rowSettings = mysqli_fetch_array($resultSettings, MYSQLI_ASSOC);
        
    $updateNotification = "Settings updated successfully to: " . $rowSettings['lang_locale'] ." <br>&nbsp;<br>";
}




$formatting = "true";

include('includes/header.php');
?>
<div class="elementBackground">
<h2>Optional Database Updates</h2>	
	<hr>
	This page has only beta status. <br>Please do only use it in testing environments!
	<hr>
	<?php echo "Your web browser identifies your language as: " . $language;?><br>
	<?php echo "Church Rota is set to use: " . $rowSettings['lang_locale'];?><br>&nbsp;<br>
	
<?php
if ($updateNotification == "") {
    ?>
	
	Do you want to update your mail templates and date settings accordingly?
		<a class="button" href='#' data-reveal-id='deleteModalUpdateSettings'>Update settings</a>
		<div id="deleteModalUpdateSettings" class="reveal-modal">
						<h1>Really update settings?</h1>
						<p>Are you sure you really want to update settings?</p><p>There is no way of undoing this action.</p>
						<p><a class="button" href="update.settings.php?action=update">Sure, update settings</a></p>
						<a class="close-reveal-modal">&#215;</a>
		</div>
<?php
} else {
        echo $updateNotification;
    }?>	
</div>	
		
<?php include('includes/footer.php'); ?>