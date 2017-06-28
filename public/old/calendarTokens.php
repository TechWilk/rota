<?php namespace TechWilk\Rota;

use DateInterval;
use DateTime;

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

$sessionUserId = $_SESSION["userid"];

// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST["method"];
  
    switch ($method) {
    case 'revoke':
      $id = $_POST["id"];
      $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
      
      revokeCalendarToken($id);
      header("Location: calendarTokens.php");
      break;
    
    default:
      // generate new URL
      $format = $_POST["format"];
      $description = $_POST["description"];
      
      if (empty($format)) {
          $error = "Format must be set";
          break;
      }
      if (empty($description)) {
          $error = "Device or software name cannot be empty";
          break;
      }
      $count = CalendarTokenQuery::create()->filterByUserId($sessionUserId)->filterByDescription($description)->filterByRevoked(false)->count();
      if ($count > 0) {
          $error = "You already have a calendar for the same device or software name. Please revoke the existing token or use a different name.";
          break;
      }
      
      $token = createCalendarToken($sessionUserId, $format, $description);
      $url = siteSettings()->getSiteUrl()."/calendar.php?user=$sessionUserId&format=$format&token=$token";
      break;
  }
}


// fetch existing calendar URLs
$calendars = calendarTokensForUser($sessionUserId);




// ~~~~~~~~~~ Presentation ~~~~~~~~~~~~



$formatting = "true";
include('includes/header.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Calendar Syncing
      <small>Settings</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="settings.php">Settings</a></li>
      <li class="active">Locations</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">

      <div class="col-sm-12 col-md-12 col-lg-12">

        <?php if ($error): ?>
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-warning"></i> Error</h4>
            <p>Failed to create new calendar URL with the following errors: 
              <code><?php echo $error; ?></code>
            </p>
          </div>
        <?php endif; ?>

        <?php if ($url): ?>
          <div class="box box-success box-solid">
            <div class="box-header">
              <h2 class="box-title">Calendar URL for <?php echo $description ?></h2>
            </div>
            <div class="box-body">
                  <p>Copy and paste the following URL into your calendar package:</p>
                  <code><?php echo $url; ?></code>
                  <p>You will not be shown this URL again, however you can generate more URL's if required.</p>
            </div><!-- /.box-body -->
            <div class="box-footer">
              <button class="btn btn-primary" type="button" data-widget="remove" onclick="window.onbeforeunload = false">I have entered the URL to my calendar software</button>
            </div>
            <script>
            window.onbeforeunload = function() {
              return "Have you copied the calendar URL?\n You cannot view this URL again, however you can generate a new one.";
            }
          </script>
          </div><!-- /.box -->
        <?php endif; ?>

      </div><!-- /.col -->

      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="box box-primary">
          <div class="box-header">
            <h2 class="box-title">My Calendars</h2>
          </div>
          <div class="box-body">
            <?php echo empty($calendars) ? "<p>none</p>" : "" ?>
            <?php foreach ($calendars as $calendar): ?>
                <?php echo $calendar->revoked ? "<s>" : "" ?>
                <p><?php echo $calendar->description." (".$calendar->format.")"; ?></p>
                <?php if (!$calendar->revoked): ?>
                <form action="#" method="post">
                  <input type="hidden" name="method" value="revoke" />
                  <input type="hidden" name="id" value="<?php echo $calendar->id ?>" />
                  <button class="btn btn-danger">Revoke</button>
                </form>
                <?php endif ?>
                <?php echo $calendar->revoked ? "</s>" : "" ?>
            <?php endforeach; ?>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

      </div><!-- /.col -->

      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="box box-primary">
          <div class="box-header">
            <h2 class="box-title">Create URL</h2>
          </div><!-- /.box-header -->
          <div class="box-body">
            <form action="#" method="post" id="addSkill">
              <fieldset>
                <div class="form-group">
                  <label for="format">Format:</label>
                  <select class="form-control" id="format" name="format">
                    <option value="ical">iCal</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="description">Device or Software name:</label>
                  <input class="form-control" id="description" name="description" type="text" placeholder="Bob's iPad, Sync to Google Calendar, etc." />
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <input class="btn btn-primary" type="submit" value="Generate calendar URL" />
              </div><!-- /.box-footer -->
            </fieldset>
          </form>
        </div><!-- /.box -->

        <div class="box box-solid">
          <div class="box-header">
            <h2 class="box-title">Instructions</h2>
          </div><!-- /.box-header -->
          <div class="box-body">
            <p>Generate a unique URL and the rota system will automatically add events to your digital calendar when you are on the rota.</p>
            <p>After generating a URL, you can add it to your favourite calendar software.  Instructions for common calendars are below.</p>
            <ul>
              <li><a href="https://support.google.com/calendar/answer/37100" target="_blank">Google Calendar</a> (follow instructions for adding with a link.)</li>
              <li><a href="https://support.apple.com/kb/PH11523" target="_blank">Apple Calendar</a></li>
              <li><a href="https://support.office.com/en-us/article/View-and-subscribe-to-Internet-Calendars-f6248506-e144-4508-b658-c838b6067597?ui=en-US&rs=en-US&ad=US&fromAR=1" target="_blank">Outlook</a> (follow instructions for adding internet calendar subscription.)</li>
            </ul>
          </div>
        </div>

      </div><!-- /.col -->


<?php include('includes/footer.php'); ?>
