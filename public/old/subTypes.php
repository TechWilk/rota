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
$subTypeId = getQueryStringForKey('subType');
$method = getQueryStringForKey('method');

if ($method == 'remove' && !empty($subTypeId)) {
    removeEventSubType($subTypeId);
}

// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($method == 'add') {
        $name = $_POST['name'];
        $name = mysqli_real_escape_string(db(), $name);

        $description = $_POST['description'];
        $description = mysqli_real_escape_string(db(), $description);

        $sql = ("INSERT INTO eventSubTypes (name, description) VALUES ('$name', '$description')");
        if (!mysqli_query(db(), $sql)) {
            die('Error: '.mysqli_error(db()));
        }
    } else {
        // Otherwise we are dealing with edits, not new stuff
        // Handle renaming of the titles
        $formindex = $_POST['formindex'];
        $description = $_POST['description'];

        $formArray = array_combine($formindex, $description);

        while (list($key, $valadd) = each($formArray)) {
            updateEventSubType($key, $valadd);
        }
    }

    // After we have inserted the data, we want to head back to the main users page
     header('Location: subTypes.php'); // Move to the home page of the admin section
      exit;
}
include 'includes/header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Event Sub-Types
      <small>Settings</small>
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
  		<h2 class="box-title">Edit event sub-types</h2>
		</div><!-- /.box-header -->
    <div class="box-body">
        <form action="editeventtype.php" method="post">
        <fieldset>

		<?php $sql = 'SELECT * FROM eventSubTypes ORDER BY name';
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<input type="hidden" name="formindex[]" value="'.$row['id'].'" />';
        echo "<input name='name[]' value='".$row['name']."' />";

        echo " <a href='subTypes.php?method=remove&subType=".$row['id']."'><i class='fa fa-times'></i></a><br />";
    } ?>
   </div><!-- /.box-body -->
   <div class="box-footer">
     </fieldset>
     <input class="btn btn-primary" type="submit" value="Update all" />
	 </form>
 </div><!-- /.box-footer -->
 </div><!-- /.box -->

 <div class="box box-primary">
   <div class="box-header">
  	 <h2 class="box-title">Add a new event type</h2>
   </div><!-- /.box-header -->
   <div class="box-body">
		<form action="subTypes.php?method=add" method="post" id="addSubType">
		<fieldset>
      <div class="form-group">
    		<label for="name">Name:</label>
    		<input class="form-control" id="name" name="name" type="text" placeholder="Holy Communion" />
      </div>
			<div class="form-group">
    		<label for="description">Description:</label>
    		<input class="form-control" id="description" name="description" type="text" placeholder="A service, once a month where..." />
      </div>
    </div><!-- /.box-body -->
    <div class="box-footer">
  		<input class="btn btn-primary" type="submit" value="Add" />
    </div><!-- /.box-footer -->

		</fieldset>
	</form>
</div><!-- /.box -->
<?php
if (isAdmin()) {
        ?>
<div id="right">
		<div class="item"><a href="settings.php">Back to settings</a></div>
</div>
<?php
    } ?>
<?php include 'includes/footer.php'; ?>
