<?php
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

$action = $_GET['action'];

if (isAdmin() && isset($_GET["id"])) {
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
} else {
    $id = $_SESSION['userid'];
}



// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requiredPasswordLength = 5;
    
    $oldPassword = $_POST['oldpassword'];
    $newPassword = $_POST['newpassword'];
    $checkPassword = $_POST['checkpassword'];

    if ($newPassword != $checkPassword) {
        $status = "new_passwords_not_match";
    }
    if (strlen($newPassword) < $requiredPasswordLength) {
        $status = "password_length_too_short";
    } else {
        if (isAdmin()) {
            forceChangePassword($id, $newPassword);
        } elseif (!isPasswordCorrectWithId($id, $oldPassword)) {
            $status = "old_password_incorrect";
        } else {
            changePassword($id, $newPassword, $oldPassword);
        }
    }

    if (isPasswordCorrectWithId($id, $newPassword)) {
        $status = "success";
    }

    if ($debug) {
        insertStatistics("user", __FILE__, "pwd_change", $status);
    }
} else {
    //no POST -> we are in edit mode
        $sql = "SELECT * FROM cr_users WHERE id = '$id'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $firstname = $row['firstName'];
    $lastname = $row['lastName'];
}





# ----------- Presentation ------------





include('includes/header.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $actionName ?> Change Password
			<small>Users</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="/users.php">Users</a></li>
			<li class="active">Edit Password</li>
		</ol>
	</section>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">

    <?php switch ($status) {
      case 'success': ?>
      <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Password Changed</h4>
        <p>Your password has been successfully changed.</p>
      </div>
      <?php  break;
      case 'new_passwords_not_match': ?>
      <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Password not changed</h4>
        <p>New passwords don't match, please try again.</p>
      </div>
      <?php  break;
      case 'old_password_incorrect': ?>
      <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Password not changed</h4>
        <p>Old password is incorrect, please try again.</p>
      </div>
      <?php  break;
            case 'password_length_too_short': ?>
      <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Password not changed</h4>
        <p>Password must be at least <?php echo $requiredPasswordLength; ?> characters long, please try again.</p>
      </div>
      <?php  break;
    } // end of switch?>

<div class="box box-primary">
  <div class="box-header">
    <h2 class="box-title">Change password - <?php echo $firstname . " " . $lastname . "<br>"; ?></h2>
  </div><!-- /.box-header -->
  <div class="box-body">

<form action="#" method="post" id="addUser">
		<fieldset>
			<?php if (!isAdmin()): ?>
			<div class="form-group">
  			<label for="oldpassword" >Old password:</label>
  			<input class="form-control" name="oldpassword" id="oldpassword" type="password" />
			</div>
			<?php endif; ?>
				
      <div class="form-group">
  			<label for="newpassword">New password:</label>
  			<input class="form-control" name="newpassword" id="newpassword" type="password" />
      </div><!-- /.form-group -->

      <div class="form-group">
  			<label for="checkpassword">Verify:</label>
  			<input class="form-control" id="checkpassword" name="checkpassword" type="password" />
      </div><!-- /.form-group -->


    </div><!-- /.box-body -->
    <div class="box-footer">
			<input class="btn btn-primary" type="submit" value="Change Password" />
    </div><!-- /.box-footer -->
		</fieldset>
	</form>

</div>

<div id="right">
		<div class="item"><a href="addUser.php?action=edit&user=<?php echo $_SESSION['userid']; ?>">Edit my account</a></div>
</div>
<?php include('includes/footer.php'); ?>
