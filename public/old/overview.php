<?php

namespace TechWilk\Rota;

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

$action = $_GET['action'];

// If the form has been sent, we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['subject'];
    //$subject = mysqli_real_escape_string(db(),$subject);

    $message = $_POST['message'];
    //$message = mysqli_real_escape_string(db(),$message);

    $sentSuccess = notifyOverview($subject, $message);
}

$overviewArr = notifyOverview('', '');
$queryRcpt = 'SELECT COUNT(email) AS count FROM users WHERE isOverviewRecipient = 1';
$resultRcpt = mysqli_query(db(), $queryRcpt) or die(mysqli_error(db()));
$rowRcpt = mysqli_fetch_array($resultRcpt, MYSQLI_ASSOC);
$rowRcptCnt = $rowRcpt['count'];
$hint = 'This message will be sent to ALL users flagged as "Overview Recipient".';
$title = 'Rota Overview';

$formatting = 'true';

include 'includes/header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Email
			<small>Rotas</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Rotas</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="alert alert-warning">
			<h4><i class="icon fa fa-info"></i>This function has not been fully tested.</h4>
			<p>Please be aware that this email function may not work as intended.</p>
		</div>
			
		<div class="box box-primary">
			<div class="box-header with-border">
				<h2 class="box-title"><?php echo $title; ?></h2>
			</div>
		  <?php if ($sentSuccess == ''): ?>
			<form action="#" method="post" id="settings">
				<fieldset>
					<div class="box-body">
						<label class="settings"><?php echo $hint; ?></label>

						<div class="form-group">
							<label class="form-content" for="subject">Subject:</label>
							<input class="form-control" name="subject" id="subject" type="text" value="<?php echo $overviewArr[0]; ?>"  />
						</div>

						<div class="form-group">
							<label class="form-content" for="message">Message to <?php echo $rowRcptCnt; ?> user/s:</label>
							<textarea class="mceNoEditor form-control" rows="14" id="message" type="text" name="message"><?php echo $overviewArr[1]; ?></textarea>
						</div>
					</div>
					<div class="box-footer">
						<?php if ($action == '') {
    ?>
						<input class="btn btn-primary" type="submit" value="Send email" class="settings" />
						<?php
} ?>
					</div>
				</fieldset>
			</form>

		  <?php
            else:
                echo '<div class="box-body">'.$sentSuccess.'</div>';
            endif;
            ?>
				</div>
			</div>

<?php include 'includes/footer.php'; ?>
