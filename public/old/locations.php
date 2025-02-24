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
$locationID = getQueryStringForKey('locationID');
$locationremove = getQueryStringForKey('locationremove');

if ($locationremove == 'true') {
    removelocation($locationID);
}

// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($editableaction == 'edit') {
        $editid = str_replace('title', '', $_POST['id']);
        $type = $_POST['type'];
        $name = $_POST['value'];
        $editableaction = $_POST['editableaction'];

        if ($type == 'title') {
            $sql = "UPDATE locations SET name = ? WHERE id = ?";
            $stmt = mysqli_prepare(db(), $sql);
            mysqli_stmt_bind_param($stmt, 'si', $name, $editid);
            mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
            mysqli_stmt_close($stmt);
        }
    } else {
        $newlocation = strip_tags($_POST['newlocation']);

        $rehearsal = $_POST['rehearsal'];
        $rehearsal = strip_tags($rehearsal);

        $sql = "INSERT INTO locations (name) VALUES (?)";
        $stmt = mysqli_prepare(db(), $sql);
        mysqli_stmt_bind_param($stmt, 's', $newlocation);
        mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
        mysqli_stmt_close($stmt);

        // After we have inserted the data, we want to head back to the main users page
        header('Location: locations.php'); // Move to the home page of the admin section
        exit;
    }
}

// ~~~~~~~~~~~~ Presentation ~~~~~~~~~~~~

$formatting = 'true';
$sendurl = 'locations.php';
include 'includes/header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Locations
      <small>Settings</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>/settings.php">Settings</a></li>
      <li class="active">Locations</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

<div class="box box-primary">
  <div class="box-header">
		<h2 class="box-title">Edit locations</h2>
  </div>
  <div class="box-body">
		<p>
		<?php $sql = 'SELECT * FROM locations ORDER BY name';
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $locationID = $row['id'];
        echo "<span id='".$locationID."' class='edit'>".$row['name'].'</span> ';
        echo " <a href='locations.php?locationremove=true&locationID=".$locationID."'><i class='fa fa-close'></i></a><br />";
    } ?>
 </div><!-- /.box-body -->
</div><!-- /.box -->
<div class="box box-primary">
 <div class="box-header">
	 <h2 class="box-title">Add location:</h2>
 </div><!-- /.box-header -->
 <div class="box-body">
		<form action="#" method="post" id="addSkill">
  		<fieldset>
    		<label for="newlocation">Name:</label>
    		<input class="form-control" id="newlocation" name="newlocation" type="text" placeholder="St Someone's Church" />

      </div><!-- /.box-body -->

      <div class="box-footer">
        <input class="btn btn-primary" type="submit" value="Add new location" />

      </div><!-- /.box-footer -->

		</fieldset>
	</form>
</div><!-- /.box -->
  <?php
  if (isAdmin()) {
      ?>
  <div id="right">
  		<div class="item"><a href="settings.php">Back to settings</a></div>
<?php
  } ?>
<?php include 'includes/footer.php'; ?>
