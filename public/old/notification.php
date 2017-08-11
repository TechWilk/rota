<?php namespace TechWilk\Rota;

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
include 'includes/config.php';
include 'includes/functions.php';

// Start the session. This checks whether someone is logged in and if not redirects them
session_start();

$sessionUserID = $_SESSION['userid'];

if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
    // Just continue the code
} else {
    $notificationId = getQueryStringForKey('id');
    $referer = getQueryStringForKey('click');
    $_SESSION['redirectUrl'] = siteSettings()->getSiteUrl().'/notification.php?id='.$notificationId.'&click='.$referer;
    header('Location: login.php');
    exit;
}

// Dismiss notification on POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'dismiss') {
        $notificationId = $_POST['id'];
        dismissNotification($notificationId);
        header('Location: index.php');
        exit;
    }
}

// Handle details from the header
$notificationId = getQueryStringForKey('id');
$referer = getQueryStringForKey('click');

$notificationId = filter_var($notificationId, FILTER_SANITIZE_NUMBER_INT);

if (!empty($notificationId)) {
    seenNotification($notificationId, $referer);

  // redir if notification has URL
  $redir = notificationLink($notificationId);
    if (!empty($redir)) {
        header('Location: '.$redir);
    }

  // find notification
  $notification = notificationWithId($notificationId);
}

    // ------ Presentation --------

$formatting = 'light';
include 'includes/header.php';
$Parsedown = new Parsedown();
?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Notification
      <small>Notifications</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Rotas</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="box box-primary">
      <div class="box-header">
        <h2 class="box-title"><?php echo $notification->summary ?></h2>
      </div>
      <div class="box-body">
        <?php echo $Parsedown->text(htmlspecialchars($notification->body)); ?>
      </div>
      <div class="box-footer">
        <form action="notification.php" method="post">
          <input type="hidden" name="action" value="dismiss">
          <input type="hidden" name="id" value="<?php echo $notificationId ?>">
          <button class="btn btn-primary">Dismiss</button>
        </form>
      </div>
    </div>
      
<?php include 'includes/footer.php'; ?>