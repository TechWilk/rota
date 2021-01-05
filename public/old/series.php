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
$seriesId = getQueryStringForKey('series');
$method = getQueryStringForKey('method');

if ($method == 'remove') {
    removeSeries($seriesId);
    header('Location: series.php'); // Remove query string from URL
    exit;
}

// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($method == 'edit') {
        $editid = $_POST['id'];
        $type = $_POST['type'];
        $name = $_POST['seriesName'];
        $description = $_POST['seriesDescription'];
        $editid = str_replace('title', '', $editid);
        if ($type == 'title') {
            $sql = "UPDATE eventGroups SET name = '$name', description = '$description' WHERE id = '$editid'";
        }
        if (!mysqli_query(db(), $sql)) {
            exit('Error: '.mysqli_error(db()));
        }
    } else {
        $name = $_POST['seriesName'];
        $description = $_POST['seriesDescription'];

        $name = mysqli_real_escape_string(db(), trim($name));
        $description = mysqli_real_escape_string(db(), trim($description));

        if (empty($name) || empty($description)) {
            $message = 'Please enter both name and description.';
        } else {
            $sql = ("INSERT INTO eventGroups (name, description) VALUES ('$name', '$description')");
            if (!mysqli_query(db(), $sql)) {
                exit('Error: '.mysqli_error(db()));
            }

            // After we have inserted the data, we want to head back to the main users page
            header('Location: series.php'); // Move to the home page of the admin section
        exit;
        }
    }
}
$formatting = 'true';
$sendurl = 'locations.php';
include 'includes/header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Series
      <small>Settings</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>/settings.php">Settings</a></li>
      <li class="active">Series</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

<div class="box box-primary">
  <div class="box-header">
		<h2 class="box-title">Edit Series</h2>
  </div>
  <div class="box-body">
		<p>
		<?php $sql = 'SELECT * FROM eventGroups WHERE archived = false ORDER BY name';
    $result = mysqli_query(db(), $sql) or exit(mysqli_error(db()));

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $seriesId = $row['id'];
        echo "<span id='".$seriesId."' class='edit'><strong>".$row['name'].'</strong></span>';
        echo " <a href='series.php?method=remove&series=".$seriesId."'><i class='fa fa-close'></i></a><br />";
        echo "<p id='".$seriesId."' class='edit'>".$row['description'].'</p>';
        echo '<hr />';
    } ?>
 </div><!-- /.box-body -->
</div><!-- /.box -->
<?php if (!empty($message)) { ?>
	<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-exclamation-triangle"></i> Error</h4>
		<?php echo $message; ?>
	</div>
<?php } ?>
<div class="box box-primary">
 <div class="box-header">
	 <h2 class="box-title">Add Series:</h2>
 </div><!-- /.box-header -->
 <div class="box-body">
		<form action="#" method="post" id="addSeries">
  		<fieldset>
				<div class="form-group">
	    		<label for="seriesName">Name:</label>
	    		<input class="form-control" id="seriesName" name="seriesName" type="text" placeholder="Enter series name" value="<?php if (isset($name)) {
        echo $name;
    } ?>"/>
				</div>
				<div class="form-group">
					<label for="seriesDescription">Description:</label>
					<textarea class="form-control mceNoEditor" rows="3" id="seriesDescription" name="seriesDescription" placeholder="Enter series description" value="<?php if (isset($description)) {
        echo $description;
    } ?>"></textarea>
				</div>

      </div><!-- /.box-body -->

      <div class="box-footer">
        <input class="btn btn-primary" type="submit" value="Add series" />

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
