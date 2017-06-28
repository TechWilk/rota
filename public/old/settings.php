<?php namespace TechWilk\Rota; use DateInterval; use DateTime;
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

$action = getQueryStringForKey('action');
$userID = getQueryStringForKey('id');

// If the form has been sent, we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $siteurl = $_POST['siteurl'];
    $notificationemail = $_POST['notificationemail'];
    $notificationemail = mysqli_real_escape_string(db(), $notificationemail);
    $siteadminemail = $_POST['siteadminemail'];
    $siteadminemail = mysqli_real_escape_string(db(), $siteadminemail);
    $norehearsalemail = $_POST['norehearsalemail'];
    $norehearsalemail = mysqli_real_escape_string(db(), $norehearsalemail);
    $yesrehearsal = $_POST['yesrehearsal'];
    $yesrehearsal = mysqli_real_escape_string(db(), $yesrehearsal);
    $newusermessage = $_POST['newusermessage'];
    $newusermessage = mysqli_real_escape_string(db(), $newusermessage);
    $owner = $_POST['owner'];

    $lang_locale = $_POST['lang_locale'];
    $lang_locale = mysqli_real_escape_string(db(), $lang_locale);
    //$event_sorting_latest = $_POST['event_sorting_latest'];
    //$event_sorting_latest = mysqli_real_escape_string(db(),$event_sorting_latest);
        if (isset($_POST['event_sorting_latest'])) {
            $event_sorting_latest = '1';
        } else {
            $event_sorting_latest = '0';
        }

    //$snapshot_show_two_month = $_POST['snapshot_show_two_month'];
    //$snapshot_show_two_month = mysqli_real_escape_string(db(),$snapshot_show_two_month);
        if (isset($_POST['snapshot_show_two_month'])) {
            $snapshot_show_two_month = '1';
        } else {
            $snapshot_show_two_month = '0';
        }

    //$snapshot_reduce_skills_by_group = $_POST['snapshot_reduce_skills_by_group'];
    //$snapshot_reduce_skills_by_group = mysqli_real_escape_string(db(),$snapshot_reduce_skills_by_group);
        if (isset($_POST['snapshot_reduce_skills_by_group'])) {
            $snapshot_reduce_skills_by_group = '1';
        } else {
            $snapshot_reduce_skills_by_group = '0';
        }

    //$logged_in_show_snapshot_button = $_POST['logged_in_show_snapshot_button'];
    //$logged_in_show_snapshot_button = mysqli_real_escape_string(db(),$logged_in_show_snapshot_button);
        if (isset($_POST['logged_in_show_snapshot_button'])) {
            $logged_in_show_snapshot_button = '1';
        } else {
            $logged_in_show_snapshot_button = '0';
        }

    if (isset($_POST['users_start_with_myevents'])) {
        $users_start_with_myevents = '1';
    } else {
        $users_start_with_myevents = '0';
    }

    if (isset($_POST['debug_mode'])) {
        $debug_mode = '1';
    } else {
        $debug_mode = '0';
    }

    $time_format_long = $_POST['time_format_long'];
    $time_format_long = mysqli_real_escape_string(db(), $time_format_long);

    $time_format_normal = $_POST['time_format_normal'];
    $time_format_normal = mysqli_real_escape_string(db(), $time_format_normal);

    $time_format_short = $_POST['time_format_short'];
    $time_format_short = mysqli_real_escape_string(db(), $time_format_short);

    $time_only_format = $_POST['time_only_format'];
    $time_only_format = mysqli_real_escape_string(db(), $time_only_format);

    $date_only_format = $_POST['date_only_format'];
    $date_only_format = mysqli_real_escape_string(db(), $date_only_format);

    $day_only_format = $_POST['day_only_format'];
    $day_only_format = mysqli_real_escape_string(db(), $day_only_format);

    $time_zone = $_POST['time_zone'];
    $time_zone = mysqli_real_escape_string(db(), $time_zone);

    $google_group_calendar = $_POST['google_group_calendar'];
    $google_group_calendar = mysqli_real_escape_string(db(), $google_group_calendar);

    $overviewemail = $_POST['overviewemail'];
    $overviewemail = mysqli_real_escape_string(db(), $overviewemail);

    $skin = $_POST['skin'];
    $skin = mysqli_real_escape_string(db(), $skin);

    if (isset($_POST['group_sorting_name'])) {
        $group_sorting_name = '1';
    } else {
        $group_sorting_name = '0';
    }
    $days_to_alert = $_POST['days_to_alert'];
    $days_to_alert = mysqli_real_escape_string(db(), $days_to_alert);
    $token = $_POST['token'];
    $token = mysqli_real_escape_string(db(), $token);

        // Update the database rather than insert new values
        $sql = "UPDATE cr_settings SET siteurl = '$siteurl', notificationemail = '$notificationemail', adminemailaddress = '$siteadminemail', norehearsalemail = '$norehearsalemail', yesrehearsal = '$yesrehearsal', newusermessage = '$newusermessage', owner = '$owner',
		lang_locale='$lang_locale',
		event_sorting_latest='$event_sorting_latest',
		snapshot_show_two_month='$snapshot_show_two_month',
		snapshot_reduce_skills_by_group='$snapshot_reduce_skills_by_group',
		logged_in_show_snapshot_button='$logged_in_show_snapshot_button',
		users_start_with_myevents='$users_start_with_myevents',
		time_format_long='$time_format_long',
		time_format_normal='$time_format_normal',
		time_format_short='$time_format_short',
		time_only_format='$time_only_format',
		date_only_format='$date_only_format',
		day_only_format='$day_only_format',
		time_zone='$time_zone',
		google_group_calendar='$google_group_calendar',
		overviewemail='$overviewemail',
		debug_mode='$debug_mode',
		token='$token',
		days_to_alert='$days_to_alert',
		group_sorting_name='$group_sorting_name',
		skin='$skin'"
        ;


    if (!mysqli_query(db(), $sql)) {
        die('Error: ' . mysqli_error(db()));
    }
    header('Location: settings.php');
}

include('includes/header.php');
?>







<?php
$sql = "SELECT * FROM cr_settings";
$result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Settings
			<?php echo "<small>v".$row['version']."</small>"; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Settings</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
	
	<div class='box'>
		<div class="box-body">
			<a class="btn" href="series.php">Edit sermon series</a>
			<a class="btn" href="editeventtype.php">Edit event types</a>
			<a class="btn" href="subTypes.php">Edit event sub types</a>
			<a class="btn" href="roles.php">Edit roles</a>
			<!--div class="item"><a href="bandskills.php">Edit band skills</a></div-->
			<a class="btn" href="locations.php">Edit Locations</a>
			<a class="btn" href="statistics.php">View Statistics</a>
		</div>
	</div>

<div class="box box-primary">
	<form action="#" method="post" id="settings">
		<fieldset>
			<div class="box-header">
				<h2 class="box-title">Administrative Infos</h2>
			</div>
			<div class="box-body" >
				<div class="form-group">
					<label class="form-content" for="siteurl">Enter your organisation name:</label>
					<input class="form-control" name="owner" id="owner" type="text" value="<?php echo $row['owner']; ?>"  />
				</div>

				<div class="form-group">
					<label class="form-content" for="siteurl">Enter URL of your website: (i.e. <code>https://yourchurch.org/rota</code>)</label>
					<input class="form-control" name="siteurl" id="siteurl" type="text" value="<?php echo $row['siteurl']; ?>"  />
				</div>

				<div class="form-group">
					<label class="form-content" for="siteadminemail">Enter your admin email address:</label>
					<input class="form-control" name="siteadminemail" id="siteadminemail" type="text" value="<?php echo $row['adminemailaddress']; ?>"  />
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->

		<div class="box box-primary collapsed-box">

			<div class="box-header">
				<h2 class="box-title">Email Templates</h2>
				<div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
			</div>
			<div class="box-body" >

				<div class="form-group">
					<label class="settings" for="notificationemail">Enter the text you would like at the bottom of a notification email:</label>
					<textarea id="notificationemail" class="form-control" rows="16" type="text" name="notificationemail"><?php echo $row['notificationemail']; ?></textarea>
				</div>

				<div class="form-group">
					<label class="settings" for="norehearsalemail">Text to display for events with no rehearsal</label>
					<textarea id="norehearsalemail" class="form-control" rows="3" type="text" name="norehearsalemail"><?php echo $row['norehearsalemail']; ?></textarea>
				</div>

				<div class="form-group">
					<label class="settings" for="yesrehearsal">Text to display for advertising rehearsal</label>
					<textarea id="yesrehearsal" class="form-control" rows="3" type="text" name="yesrehearsal"><?php echo $row['yesrehearsal']; ?></textarea>
				</div>

				<div class="form-group">
					<label class="settings" for="newusermessage">Email to new users</label>
					<textarea id="newusermessage" class="form-control" rows="20" type="text" name="newusermessage"><?php echo $row['newusermessage']; ?></textarea>
				</div>

				<div class="form-group">
						<label class="settings" for="overviewemail">Email for monthly overview</label>
					<textarea id="overviewemail" class="form-control" rows="10" type="text" name="overviewemail"><?php echo $row['overviewemail']; ?></textarea>
				</div>
			</div>
		</div>

		<div class="box box-primary collapsed-box">
			<div class="box-header">
				<h2 class="box-title">Locale and Timezone</h2>
				<div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
			</div>
			<div class="box-body">

				<div class="form-group">
					<label class="form-content" for="lang_locale">Language locale (e.g. en_GB, see <a href="http://www.php.net/manual/en/function.setlocale.php" target="_blank">php setlocale</a>) and host system's locals: <em><?php //echo " - " . setlocale(LC_ALL,null);?></em></label>
					<input class="form-control" name="lang_locale" id="lang_locale" type="text" value="<?php echo $row['lang_locale']; ?>"  />
				</div>

				<div class="form-group">
					<label class="form-content" for="time_format_long">Long time format (pattern see <a href="http://www.php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters" target="_blank">php strftime</a>):</label>
					<input class="form-control" name="time_format_long" id="time_format_long" type="text" value="<?php echo $row['time_format_long']; ?>"  />
				</div>

				<div>
					<label class="form-content" for="time_format_normal">Standard time format (pattern see <a href="http://www.php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters" target="_blank">php strftime</a>):</label>
					<input class="form-control" name="time_format_normal" id="time_format_normal" type="text" value="<?php echo $row['time_format_normal']; ?>"  />
				</div>

				<div class="form-group">
					<label class="form-content" for="time_format_short">Short time format (pattern see <a href="http://www.php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters" target="_blank">php strftime</a>):</label>
					<input class="form-control" name="time_format_short" id="time_format_short" type="text" value="<?php echo $row['time_format_short']; ?>"  />
				</div>

				<div class="form-group">
					<label class="form-content" for="time_only_format">Time only (pattern see <a href="http://www.php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters" target="_blank">php strftime</a>):</label>
					<input class="form-control" name="time_only_format" id="time_only_format" type="text" value="<?php echo $row['time_only_format']; ?>"  />
				</div>

				<div class="form-group">
					<label class="form-content" for="date_only_format">Date only (pattern see <a href="http://www.php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters" target="_blank">php strftime</a>):</label>
					<input class="form-control" name="date_only_format" id="date_only_format" type="text" value="<?php echo $row['date_only_format']; ?>"  />
				</div>

				<div class="form-group">
					<label class="form-content" for="day_only_format">Date only (pattern see <a href="http://www.php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters" target="_blank">php strftime</a>):</label>
					<input class="form-control" name="day_only_format" id="day_only_format" type="text" value="<?php echo $row['day_only_format']; ?>"  />
				</div>

				<div class="form-group">
					<label class="form-content" for="time_zone">Time Zone (see <a href="http://www.php.net/manual/en/timezones.php" target="_blank">php "List of Supported Timezones"</a>, e.g. Europe/London):</label>
					<input class="form-control" name="time_zone" id="time_zone" type="text" value="<?php echo $row['time_zone']; ?>"  />
				</div>
			</div>
		</div>

		<div class="box box-primary collapsed-box">
			<div class="box-header">
				<h2 class="box-title">Application Behaviour</h2>
				<div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
			</div>
			<div class="box-body" >
				
				<div class="form-group">
					<label for="skin">Application skin:</label>
					<select name="skin" id="skin" class="form-control">
						<?php $skinOptions = ['skin-blue-light' => 'Blue &amp; Light',
                                                                    'skin-blue' => 'Blue &amp; Dark',
                                                                    'skin-yellow-light' => 'Yellow &amp; Light',
                                                                    'skin-yellow' => 'Yellow &amp; Dark',
                                                                    'skin-green-light' => 'Green &amp; Light',
                                                                    'skin-green' => 'Green &amp; Dark',
                                                                    'skin-purple-light' => 'Purple &amp; Light',
                                                                    'skin-purple' => 'Purple &amp; Dark',
                                                                    'skin-red-light' => 'Red &amp; Light',
                                                                    'skin-red' => 'Red &amp; Dark',
                                                                    'skin-black-light' => 'Black &amp; Light',
                                                                    'skin-black' => 'Black &amp; Dark'];
                                                                    
    foreach ($skinOptions as $skinName => $skinDescription): ?>
						<option value="<?php echo $skinName ?>" <?php echo ($skinName == $row['skin']) ? 'selected' : '' ?>><?php echo $skinDescription ?></option>
						<?php endforeach ?>
					</select>
				</div>

				<div class="checkbox">
					<label class="form-content" for="event_sorting_latest">
						<input class="checkbox" name="event_sorting_latest" id="event_sorting_latest" type="checkbox" value="1" <?php if ($row['event_sorting_latest']=='1') {
        echo 'checked="checked"';
    } elseif ($row['event_sorting_latest'] == '0') {
    } ?>  />Event overview - show latest events first:
					</label>
				</div><!-- /.checkbox -->

				<div class="checkbox">
					<label class="form-content" for="snapshot_show_two_month">
						<input class="checkbox" name="snapshot_show_two_month" id="snapshot_show_two_month" type="checkbox" value="1" <?php if ($row['snapshot_show_two_month']=='1') {
        echo 'checked="checked"';
    } elseif ($row['snapshot_show_two_month'] == '0') {
    } ?>  />Snapshot - show only current month (and following if current day>20):
					</label>
				</div><!-- /.checkbox -->

				<div class="checkbox">
					<label class="form-content" for="snapshot_reduce_skills_by_group">
						<input class="checkbox" name="snapshot_reduce_skills_by_group" id="snapshot_reduce_skills_by_group" type="checkbox" value="1" <?php if ($row['snapshot_reduce_skills_by_group']=='1') {
        echo 'checked="checked"';
    } elseif ($row['snapshot_reduce_skills_by_group'] == '0') {
    } ?>  />
						Snapshot - show only skills up to user's max. used skill group:
					</label>
				</div><!-- /.checkbox -->

				<div class="checkbox">
					<label class="form-content" for="group_sorting_name">
						<input class="checkbox" name="group_sorting_name" id="group_sorting_name" type="checkbox" value="1" <?php if ($row['group_sorting_name']=='1') {
        echo 'checked="checked"';
    } elseif ($row['group_sorting_name'] == '0') {
    } ?>  />
						Snapshot - sort columns by skill group and skill name:
					</label>
				</div><!-- /.checkbox -->

				<div class="checkbox">
					<label class="form-content" for="logged_in_show_snapshot_button">
						<input class="checkbox" name="logged_in_show_snapshot_button" id="logged_in_show_snapshot_button" type="checkbox" value="1" <?php if ($row['logged_in_show_snapshot_button']=='1') {
        echo 'checked="checked"';
    } elseif ($row['logged_in_show_snapshot_button'] == '0') {
    } ?>  />
						Show button "Snapshot view" for users:
					</label>
				</div><!-- /.checkbox -->

				<div class="checkbox">
					<label class="form-content" for="users_start_with_myevents">
						<input class="checkbox" name="users_start_with_myevents" id="users_start_with_myevents" type="checkbox" value="1" <?php if ($row['users_start_with_myevents']=='1') {
        echo 'checked="checked"';
    } elseif ($row['users_start_with_myevents'] == '0') {
    } ?>  />
						User starts with events filtered for "My Events":
					</label>
				</div><!-- /.checkbox -->

				<div class="checkbox">
					<label class="form-content" for="debug_mode">
						<input class="checkbox" name="debug_mode" id="debug_mode" type="checkbox" value="1" <?php if ($row['debug_mode']=='1') {
        echo 'checked="checked"';
    } elseif ($row['debug_mode'] == '0') {
    } ?>  />
						Debug Mode (adds more details about user actions to internal statistics):
					</label>
				</div><!-- /.checkbox -->

			</div><!-- /.box-body -->
		</div><!-- /.box -->

		<div class="box box-primary collapsed-box">
			<div class="box-header">
				<h2 class="box-title">External Services</h2>
				<div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
			</div>
			<div class="box-body">

				<div class="form-group">
					<label class="form-content" for="google_group_calendar">Google Group Calendar ID (for admin snapshot, e.g.&nbsp;5vpkrij4fv8k011dcmt38rt7ik@group.calendar.google.com):</label>
					<input class="form-control" name="google_group_calendar" id="google_group_calendar" type="text" value="<?php echo $row['google_group_calendar']; ?>"  />
				</div><!-- /.box-group -->

				<div class="form-group">
					<label class="form-content" for="token">Security Token (see comments in cr_daily.php for details):</label>
					<input class="form-control" name="token" id="token" type="text" value="<?php echo $row['token']; ?>"  />
				</div><!-- /.box-group -->

				<div class="form-group">
					<label class="form-content" for="days_to_alert">Days to Alert (send mail reminders X days before event; <br>0 = disable; see comments in cr_daily.php for details):</label>
					<input class="form-control" name="days_to_alert" id="days_to_alert" type="text" value="<?php echo $row['days_to_alert']; ?>"  />
				</div><!-- /.box-group -->

			</div><!-- /.box-body -->
		</div><!-- /.box -->

		<div class="box box-primary">


		<?php
}
        ?>

		<div class="box-body">
			<input class="btn btn-primary" type="submit" value="Save changes" class="settings" />
		</div>
		</fieldset>
	</form>

</div>

<?php include('includes/footer.php'); ?>
