<?php namespace TechWilk\Rota; use DateInterval; use DateTime;
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
include('includes/config.php');
$holdQuery = true;
include('includes/functions.php');



// only run install method if no users found in database
try {
    $numberOfUsers = UserQuery::create()->count();
    if ($numberOfUsers != 0) {
        header('Location: login.php');
        exit;
    }
} catch (\Exception $e) {
}

$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' .$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url = substr($url, 0, -12); // remove "/install.php" from url




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = file_get_contents('generated-sql/default.sql');
    //$sql = trim(preg_replace('/\s\s+/', ' ', $sql));

    $conn = Propel\Runtime\Propel::getConnection();
    
    try {
        //echo $sql;
        //exit;
        $conn->exec($sql);
    } catch (\Exception $e) {
        echo "Error (code: ".$e->getCode().") adding tables to database: ".$e->getMessage();
        exit;
    }

    $firstname = $_POST['firstname'];
    $firstname = trim(strip_tags($firstname));

    $lastname = $_POST['lastname'];
    $lastname = trim(strip_tags($lastname));

    $username = strtolower($firstname).".".strtolower($lastname);

    $password = $_POST['password'];

    $email = $_POST['email'];
    $email = trim(strip_tags($email));

    $mobile = $_POST['mobile'];
    $mobile = trim(strip_tags($mobile));

    $user = new User;

    $user->setFirstName($firstname);
    $user->setLastName($lastname);
    $user->setUsername($username);
    $user->setPassword($password);
    $user->setEmail($email);
    $user->setMobile($mobile);

    $user->setIsAdmin(true);

    try {
        $user->save();
    } catch (\Exception $e) {
        echo "Error (code: ".$e->getCode().") adding user to database: ".$e->getMessage();
        exit;
    }

    $owner = $_POST['owner'];
    $owner = trim(strip_tags($owner));

    $siteEmail = $_POST['siteEmail'];
    $siteEmail = trim(strip_tags($siteEmail));

    $url = $_POST['url'];
    $url = trim(strip_tags($url));


    $settings = new Settings;
    $settings->setOwner($owner);
    $settings->setSiteUrl($url);
    $settings->setAdminEmailAddress($siteEmail);
    $settings->setSkin('skin-blue-light');


    // load default settings into the database
    if ($_POST['loadDefaults'] == 'yes') {
        $msg = <<<'MSG'
Dear [name]

This is an automatic reminder.

You have the roles: [rotaoutput]
During the service on [date] in [location].
[eventdetails]
 
If you have arranged a swap, please let us know.

Many thanks for your continued service!
MSG;
        $settings->setNotificationEmail($msg);


        $msg = <<<'MSG'
There will be no rehearsal. Please come at 9.30 on Sunday morning for setup and soundcheck.
MSG;
        $settings->setNoRehearsalEmail($msg);


        $msg = <<<'MSG'
There will be a rehearsal for this service
MSG;
        $settings->setYesRehearsal($msg);


        // not quoted 'MSG' as we are passing in the variable $owner
        $msg = <<<MSG
Dear [name]

This email contains important information for you because you are on one or more teams at the church.

You have been added as a new user to the Church Rota system at [siteurl].

Your temporary user login details are as follows:
Username: [username]
Temporary password: [password]

Please make sure your contact details are correct. We also recommend you immediately change your password to something unique and memorable.

Have a look around. It's designed to be very simple to use.

If you have any questions, please feel free to get in contact with us.

Many thanks for your continued service!
$owner teams
MSG;
        $settings->setNewUserMessage($msg);


        $msg = <<<'MSG'
Dear [NAME],

In this email you find the your personal Rota for [MONTH] [YEAR] onwards.

[EVENTS]

Please request a swap online, or inform us as soon as possible, if you are not able to serve as scheduled.
MSG;
        $settings->setOverviewEmail($msg);

        $settings->setLangLocale("en_GB");
        $settings->setTimeFormatLong("%A, %B %e @ %I:%M %p");
        $settings->setTimeFormatNormal("%d/%m/%y %I:%M %p");
        $settings->setTimeFormatShort("%a, <strong>%b %e</strong>, %I:%M %p");
        $settings->setTimeOnlyFormat("%l %M %p");
        $settings->setDateOnlyFormat("");
        $settings->setDayOnlyFormat("%A, %B %e");
        $settings->setTimeZone("Europe/London");

        $settings->setLoggedInShowSnapshotButton(true);
        $settings->setUsersStartWithMyEvents(true);

        $settings->setDaysToAlert(6);
    } // end if ($loadDefaults)
    


    $settings->save();

    header('Location: login.php?username='.$username); // Move to the home page of the admin section
    exit;
}






// ~~~~~~~~~~ PRESENTATION ~~~~~~~~~~



$formatting = "light";
//include('includes/header.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Install
			<small>Church Rota</small>
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
		<h2 class="box-title">Welcome to the Church Rota</h2>
	</div>
	<div class="box-body">
		<p>Thank you for choosing to install Church Rota. We have searched the database configuration files and have been able to connect, so this is the last stage in the installation. Simply enter an administator username and password and you will be ready to go...</p>
	</div>
</div>

<form action="install.php" method="post">

	<div class="box box-primary">
		<div class="box-header">
			<h2 class="box-title">Church Details</h2>
		</div>
		<fieldset class="box-body">
			<div class="form-group">
				<label for="owner">Site Name: <em>to appear in the top left of the site</em></label>
				<input name="owner" id="owner" type="text"  class="form-control" placeholder="Enter name to appear in the top left of the site" />
			</div>
			<div class="form-group">
				<label for="siteEmail">Site Email Address: <em>used to send emails from the site</em></label>
				<input name="siteEmail" id="siteEmail" type="text"  class="form-control" placeholder="Enter first name" />
			</div>

			<div class="form-group">
				<label for="url">Site URL: <em>hopefully we've guessed correctly! (otherwise please ammend)</em></label>
				<input name="url" id="url" type="text" class="form-control" value="<?php echo $url ?>" />
			</div>
		</fieldset>
	</div>


	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title">Create Admin User</h3>
		</div>
		<fieldset class="box-body">
			<div class="form-group">
				<label for="firstname">First name:</label>
				<input name="firstname" id="firstname" type="text"  class="form-control" placeholder="Enter first name" />
			</div>
			<div class="form-group">
				<label for="lastname">Last name:</label>
				<input name="lastname" id="lastname" type="text"  class="form-control" placeholder="Enter last name" />
			</div>
			<div class="form-group">
				<label for="email">Email:</label>
				<input id="email" name="email" type="text" class="form-control" placeholder="Enter email address" />
			</div>
			<div class="form-group">
				<label for="mobile">Mobile number:</label>
				<input id="mobile" name="mobile" type="text"  class="form-control" placeholder="Enter their mobile number" />
			</div>
			<div class="form-group">
				<label for="password">Password:</label>
				<input id="password" name="password" class="form-control" type="password"  />
			</div>
		</fieldset>
	</div>

	<div class="box box-primary">
		<div class="box-header">
			<h2 class="box-title">Options</h2>
		</div>
		<fieldset class="box-body">
			<label><input type="checkbox" name="loadDefaults" value="yes" checked> Load Default settings</label>
		</div>
		<div class="box-footer">
			<input type="submit" value="Let's go!" />
		</div>
	</fieldset>

</form>




<div id="right">

</div>

<?php
$owner='A Church';
$version='0.0.0';
//include('includes/footer.php');
