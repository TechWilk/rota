<?php

namespace TechWilk\Rota;

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

// Get the query string
$id = $_GET['id'];
$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

// Approve or decline
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    switch ($_POST['action']) {
    case 'approve':
      $userId = approvePendingUser($id);
      mailNewUser($userId);
      header('Location: addUser.php?action=edit&user='.$userId);
      break;
    case 'merge':
      $existingUserId = $_POST['existingUser'];
      $existingUserId = filter_var($existingUserId, FILTER_SANITIZE_NUMBER_INT);
      mergePendingUserWithUserId($id, $existingUserId);
      mailNewUser($existingUserId);
      header('Location: addUser.php?action=edit&user='.$existingUserId);
      break;
    case 'decline':
      declinePendingUser($id);
      break;

    default:
      // code...
      break;
  }
}

if (empty($id)) {
    header('Location: index.php');
}

// setup page

$sql = "SELECT socialId, firstName, lastName, email, approved, declined, source FROM pendingUsers WHERE id = $id";
$result = mysqli_query(db(), $sql) or exit(mysqli_error(db()));
$user = mysqli_fetch_object($result);

if (!($user->approved == true || $user->declined == true)) {
    $buttonsVisible = true;
}

// ~~~~~~~~~~~~ Presentation ~~~~~~~~~~~~

include 'includes/header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Pending Users
      <small>Accounts</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>/users.php">Users</a></li>
      <li class="active">Pending</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    
<div class="box box-primary">
  <div class="box-header">
    <h2 class="box-title">
      <?php echo $user->approved ? 'Approved' : ($user->declined ? 'Declined' : 'Pending') ?>: <?php echo $user->firstName.' '.$user->lastName ?>
    </h2>
  </div>
  <div class="box-body">
    <p><strong>First name:</strong> <?php echo $user->firstName ?></p>
    <p><strong>Last name:</strong> <?php echo $user->lastName ?></p>
    <p><strong>Email address:</strong> <?php echo $user->email ?></p>
    <p><strong>Source:</strong> <?php echo $user->source ?></p>

  </div>
  <?php if ($buttonsVisible == true) { ?>
  <div class="box-footer">
    <form action="#" method="post">
      <input type="hidden" name="id" value="<?php echo $id ?>" />
      <input type="hidden" name="action" value="approve" />
      <button class="btn btn-primary">Create as new user</button>
    </form>
    <form action="#" method="post">
      <input type="hidden" name="id" value="<?php echo $id ?>" />
      <input type="hidden" name="action" value="decline" />
      <button class="btn btn-danger">Delete request</button>
    </form>
  </div>
  <?php } ?>
</div>

<?php if ($buttonsVisible == true) { ?>
  <div class="box box-primary">
    <div class="box-header">
      <h2 class="box-title">Merge with existing user</h2>
    </div>
    <div class="box-body">
      <form action="#" method="post">
        <input type="hidden" name="id" value="<?php echo $id ?>" />
        <input type="hidden" name="action" value="merge" />
        <div class="form-group">
          <label for="existingUser">Existing user</label>
          <select class="form-control" name="existingUser">
            <?php
            $users = allUsersNames();
            foreach ($users as $existingUser) { ?>
            <option value="<?php echo $existingUser->id?>" <?php echo($existingUser->firstName == $user->firstName) && ($existingUser->lastName == $user->lastName) ? "selected='selected'" : '' ?>><?php echo $existingUser->firstName.' '.$existingUser->lastName ?></option>
            <?php } ?>
          </select>
        </div>
    </div>
    <div class="box-footer">
      <button class="btn btn-primary">Merge</button>
      </form>
    </div>
  </div>
<?php } ?>

<?php include 'includes/footer.php'; ?>
