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

// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $users = $_POST['user'];
    $roleId = $_POST['role'];
    //$roleId = filter_var($roleId, FILTER_SANITIZE_NUMBER_INT);
    if (empty($users)) {
        $sql = "DELETE FROM userRoles WHERE roleId = '$roleId'";
        mysqli_query(db(), $sql) or die(mysqli_error(db()));
    } else {
        $sqlPeople = "SELECT
										userId
									FROM userRoles
									WHERE roleId = '$roleId'";

        $resultPeople = mysqli_query(db(), $sqlPeople) or die('mysqli_error(db())');

        $existingUsers = [];
        while ($viewPeople = mysqli_fetch_array($resultPeople, MYSQLI_ASSOC)) {
            $existingUsers[] = $viewPeople['userId'];
        }

        $addarray = array_diff($users, $existingUsers);

        foreach ($addarray as $userId) {
            addUserRole($userId, $roleId);
        }

        // Compare the other way to notice what's disappeared
        $deletearray = array_diff($existingUsers, $users);

        foreach ($deletearray as $userId) {
            removeUserRole($userId, $roleId);
        }
    }

    // header ( "Location: index.php#section" . $eventID);
}

// get role id from header
$roleId = $_GET['id'];

$roleId = filter_var($roleId, FILTER_SANITIZE_NUMBER_INT);

if (!$roleId) {
    header('Location: roles.php');
}

$sql = "SELECT *
FROM roles
WHERE id = '$roleId'";
$result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $id = $row['id'];
    $roleName = $row['name'];
}

$formatting = 'true';

$sqlPeople = "SELECT *,
							CONCAT(u.firstName, ' ', u.lastName) AS name,
							(SELECT roleId FROM userRoles ur WHERE ur.userId = u.id AND ur.roleId = '$roleId' LIMIT 1) AS existing
							FROM users u
							ORDER BY u.firstName";

$result = mysqli_query(db(), $sqlPeople) or die(mysqli_error(db()));

while ($viewPeople = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $users[] = $viewPeople;
}

// -------- Presentation ---------

include 'includes/header.php';
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      User Roles
      <small>Settings</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>/settings.php">Settings</a></li>
      <li class="active">Roles</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

	<div class="box box-primary">
		<div class="box-header">
			<h2 class="box-title">Edit Role: <?php echo $roleName; ?></h2>
		</div>

		<form action="editRole.php" method="post" id="editRole">
			<fieldset>
				<input type="hidden" name='role' value="<?php echo $roleId ?>" />

				<div class="box-body">

					<?php foreach ($users as $user): ?>
					<div class='checkbox'>
						<label for='user[<?php echo $user['id']; ?>]'>
							<input
								<?php echo $user['existing'] != '' ? 'checked="checked"' : '' ?>
								type='checkbox'
								id='user[<?php echo $user['id']; ?>]'
								name='user[]'
								value='<?php echo $user['id']; ?>'
								/>
							<?php echo $user['name']; ?>
						</label>
					</div>
					<?php endforeach ?>

				</div>
				<div class="box-footer">
					<input class="btn btn-primary" type="submit" value="Save" />
				</div>
			</fieldset>
		</form>
	</div>


<?php include 'includes/footer.php'; ?>
