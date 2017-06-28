<?php namespace TechWilk\Rota; use DateInterval; use DateTime;

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

// Handle details from the header
$id = $_GET['id'];
$action = $_GET['action'];
$removeresource = $_GET['removeresource'];
$editableaction = $_POST['editableaction'];

// Method to remove  someone from the band
if ($removeresource != "") {
    removeResource($id);
}

// If the form has been sent, we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resourcename = $_POST['resourcename'];
    $resourcelink = $_POST['resourcelink'];
    $resourcedescription = $_POST['resourcedescription'];

    if ($action == "editsent") {
        $editid = $_POST['id'];
        $type = $_POST['type'];
        $editid = str_replace("title", "", $editid);

        $sql = "UPDATE cr_documents SET title = '$resourcename', description = '$resourcedescription', link = '$resourcelink' WHERE id = '$id'";

        if (!mysqli_query(db(), $sql)) {
            die('Error: ' . mysqli_error(db()));
        }
    } else {
        if ($_FILES['resourcefile']['tmp_name'] == "none") {
        } else {
            $filename = $_FILES['resourcefile']['name'];
            copy($_FILES['resourcefile']['tmp_name'], "./documents/".$_FILES['resourcefile']['name']);
        }



        $sql = ("INSERT INTO cr_documents (title, description, url, link) VALUES ('$resourcename', '$resourcedescription', '$filename', '$resourcelink')");
        if (!mysqli_query(db(), $sql)) {
            die('Error: ' . mysqli_error(db()));
        }
        // After we have inserted the data, we want to head back to the main page
    }
    header('Location: resources.php');
    exit;
}
$formatting = "true";
$sendurl = "resources.php";
include('includes/header.php');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      View All
      <small>Resources</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Resources</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

<?php
if (($action == "new" || $action == "edit") && isAdmin()) {
    if ($action == "new") {
        $actionlink = "resources.php?action=newsent";
    } else {
        $actionlink = "resources.php?action=editsent&id=" . $id;
        $sql = "SELECT * FROM cr_documents WHERE id = '$id'";
        $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $resourcename = $row['title'];
            $resourcedescription = $row['description'];
            $resourcelink = $row['link'];
        }
    } ?>


<div class="box box-primary">
  <div class="box-header">
  	<h2 class="box-title">Add a new resource:</h2>
  </div><!-- ./box-header -->
	<form id="addResource" method="post" action="<?php echo $actionlink; ?>" enctype="multipart/form-data">
  	<fieldset>
      <div class="box-body">
        <div class="form-group">
    			<label for="resourcename">Resource name:</label>
    			<input class="form-control" id="resourcename" type="text" name="resourcename" value="<?php echo $resourcename; ?>" placeholder="Enter resource name:" />
        </div>


  			<?php if ($action == "edit" && $resourcelink != "") {
        ?>
  			<label for="resourcelink">Resource link:</label>
  			<input id="resourcelink" type="text" name="resourcelink" value="<?php echo $resourcelink; ?>" placeholder="Enter resource link:" />

  			<?php
    } elseif ($action == "edit" && $resourcelink == "") {
        ?>
        <p>Resource was a file upload. There is currently no way of editing this. Please delete and create anew.</p>
  			<?php
    } else {
        ?>
        <div class="form-group">
    			<label for="resourcefile">Upload:</label>
    			<input id="resourcefile" type="file" name="resourcefile" />
        </div>
  			<?php
    } ?>

        <div class="form-group">
    			<label for="resourcedescription">Resource description: <em>(Supports <a href="https://help.github.com/articles/basic-writing-and-formatting-syntax/">Github Flavoured Markdown</a>)</em></label>
    			<textarea id="resourcedescription" type="text" class="mceNoEditor form-control" rows="5" name="resourcedescription"><?php echo $resourcedescription; ?></textarea>
        </div>
      </div><!-- /.box-body -->
      <div class="box-footer">
  			<input class="btn btn-primary" type="submit" value="Add resource" />
        <a class="btn" href="resources.php">Cancel</a>
      </div><!-- /.box-footer -->
		</fieldset>
	</form>
</div><!-- /.box -->
<script src="plugins/simplemde/simplemde.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="plugins/simplemde/simplemde.min.css" media="screen" />
<script>
var simplemde = new SimpleMDE({ element: document.getElementById("resourcedescription"),
																forceSync: true });
</script>

<?php
} else {
        if (isAdmin()) {
            ?>

    <!-- row of action buttons -->
    <div class="row">

      <div class="col-md-3 col-sm-6 col-xs-12">
        <a href="resources.php?action=new">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-plus-outline"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Upload File</span>
              <span class="info-box-number">Add Resource</span>
            </div><!-- /.info-box-content -->
          </div><!-- /.info-box -->
        </a>
      </div><!-- /.col -->

    </div><!-- /.row -->

  <?php
        }

        $sql = "SELECT * FROM cr_documents ORDER BY title";
        $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    
        $Parsedown = new Parsedown();

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $resourceID = $row['id']; ?>
		<?php if (isAdmin()): ?>
		<div id="deleteModal<?php echo $resourceID; ?>" class="modal modal-danger fade" role="dialogue">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Really delete resource?</h4>
					</div>
					<div class="modal-body">
						<p>Are you sure you really want to delete the resource '<?php echo htmlspecialchars($row['title']) ?>'?<br />There is no way of undoing this action.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
						<a class="btn btn-outline" href="resources.php?removeresource=true&id=<?php echo $resourceID ?>">DELETE RESOURCE</a>
					</div>
				</div>
			</div>
		</div><!-- /.deleteModal -->
		<?php endif ?>

		<div class="box box-primary" id="resource<?php echo $resourceID; ?>">
			<div class="box-header">
				<h2 class="box-title"><?php echo htmlspecialchars($row['title']) ?></h2>
			</div>
			<div class="box-body">
				<?php echo $Parsedown->text(htmlspecialchars($row['description'])); ?>
			</div>
			<div class="box-footer">
				<a class="btn btn-primary" href="<?php echo ($row['url'] != "") ? "documents/".$row['url'] : '#' ?>">Download</a>
				<?php if (isAdmin()): ?>
				<a class="btn btn-warning" href='resources.php?action=edit&id=<?php echo $resourceID; ?>'><i class='fa fa-pencil'></i> Edit</a>
				<button type="button" class='btn btn-danger' data-toggle='modal' data-target='#deleteModal<?php echo $resourceID; ?>'><i class='fa fa-times'></i> Remove</a>
				<?php endif ?>
			</div>

		</div>

	<?php
        }
    } ?>
<?php include('includes/footer.php'); ?>
