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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $eventId = $_POST['event'];

    $eventId = filter_var($eventId, FILTER_SANITIZE_NUMBER_INT);

    if (!isset($_POST['group'])) {
        echo "You must select at least one group. Please try again.";
        exit;
    }

    $groups;
    foreach ($_POST['group'] as $group) {
        $groups[] = intval($group);
    }

    $statusMessage = '';
    $sent = sendEventEmailToGroups($eventId, $subject, $message, $groups);
    if ($sent === true) {
        $statusMessage = "Sent successfully";
    } else {
        foreach ($sent as $errorMessage) {
            $statusMessage .= '<p>'.$errorMessage.'</p>';
        }
    }

    $_SESSION['notification'] = $statusMessage;
    header('Location: index.php');
    exit;
}



$eventId = getQueryStringForKey('event');

if (is_null($eventId)) {
    $_SESSION['notification'] = 'An error occurred. Please try again.';
    header('Location: index.php');
}


$sql = "SELECT
          g.id,
          g.name,
          COUNT(DISTINCT ur.userId) AS users
        FROM
          cr_groups g
          INNER JOIN cr_roles r ON r.groupId = g.id
          INNER JOIN cr_userRoles ur ON ur.roleId = r.id
          INNER JOIN cr_eventPeople ep ON ep.userRoleId = ur.id
        WHERE
          ep.eventId = $eventId
        GROUP BY
          g.id";
$result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
while ($ob = mysqli_fetch_object($result)) {
    $groups[] = $ob;
}

$subject = getEventEmailSubject($eventId);
$message = getEventEmailMessage($eventId);





// ~~~~~~~~~~ PRESENTATION ~~~~~~~~~~



include('includes/header.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Email Event
			<small>Rotas</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Rotas</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
    <form action="#" method="post">
      <div class="box box-primary">
        <div class="box-header">
          <h2 class="box-title">Send to everyone involved in the event who is in the groups</h2>
        </div>
        <div class="box-body">
          <?php if (count($groups) > 0): ?>
            <?php foreach ($groups as $group): ?>
              <div>              
                <label><input type="checkbox" name="group[]" value="<?php echo $group->id ?>"> <?php echo $group->name ?> (<?php echo $group->users ?>)</label>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="box box-primary">
        <div class="box-header with-border">
          <h2 class="box-title">Message</h2>
        </div>
          <fieldset>
            <input name="event" type="hidden" value="<?php echo $eventId ?>"  />
            <div class="box-body">

              <div class="form-group">
                <label class="form-content" for="subject">Subject:</label>
                <input class="form-control" name="subject" id="subject" type="text" value="<?php echo $subject ?>"  />
              </div>

              <div class="form-group">
                <label class="form-content" for="message">Message:</label>
                <textarea class="mceNoEditor form-control" rows="14" id="message" type="text" name="message"><?php echo $message;?></textarea>
              </div>
            </div>
            <div class="box-footer">
              <input class="btn btn-primary" type="submit" value="Send email" class="settings" />
            </div>
          </fieldset>
        </div>
      </form>
<?php include('includes/footer.php'); ?>