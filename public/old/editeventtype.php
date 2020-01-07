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
$eventTypeId = getQueryStringForKey('id');
$method = getQueryStringForKey('method');

if ($method == 'remove' && isset($eventTypeId)) {
    removeEventType($eventTypeId);
}

// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($method == 'new') {
        $name = $_POST['name'];
        $name = trim(strip_tags($name));

        $description = $_POST['description'];
        $description = trim(strip_tags($description));

        //$defaultDay = $_POST['defaultDay'];
        //$defaultDay = filter_var($defaultDay, FILTER_SANITIZE_NUMBER_INT);

        $defaultTime = $_POST['defaultTime'];
        $defaultTime = trim(strip_tags($defaultTime));

        $defaultLocation = $_POST['defaultLocation'];
        $defaultLocation = filter_var($defaultLocation, FILTER_SANITIZE_NUMBER_INT);

        $eventType = new EventType();
        $eventType->setName($name);
        $eventType->setDescription($description);
        //$eventType->setDefaultDay($defaultDay);
        $eventType->setDefaultTime($defaultTime);

        if (strlen($defaultLocation) > 0) {
            $eventType->setDefaultLocationId($defaultLocation);
            echo 'added';
        } else {
            $eventType->setDefaultLocationId(null);
        }

        $eventType->setRehearsal(0);

        $eventType->save();
    }

    // After we have inserted the data, we want to head back to the main users page
    header('Location: editeventtype.php'); // Move to the home page of the admin section
    exit;
}

$eventTypes = EventTypeQuery::create()->find();

// ~~~~~~~~~~ PRESENTATION ~~~~~~~~~~

include 'includes/header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Event Types
      <small>Settings</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Rotas</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

<div class="row">
	<div class="col-sm-6">
		<?php foreach ($eventTypes as $eventType) { ?>
		<div class="box box-primary">
				<div class="box-header">
					<h2 class="box-title"><?php echo $eventType->getName() ?></h2>
				</div><!-- /.box-header -->
				<div class="box-body">
					<p><?php echo $eventType->getDescription() ?></p>
					<hr />
					<p>Defaults:
						<ul>
							<li><strong>Day</strong>: <?php echo $eventType->getDefaultDay() ?></li>
							<li><strong>Time</strong>: <?php echo $eventType->getDefaultTime('H:i') ?></li>
							<li><strong>Location</strong>: <?php echo $eventType->getLocation() ? $eventType->getLocation()->getName() : '' ?></li>
						</ul>
					</p>
			</div><!-- /.box-body -->
			<div class="box-footer">
				<a class="btn btn-danger" href='editeventtype.php?method=remove&id=<?php echo $eventType->getId() ?>'>
					Delete
				</a>
			</div>
		</div><!-- /.box -->
		<?php } ?>
	</div><!-- /.col -->

	<div class="col-sm-6">
		<div class="box box-primary">
			<div class="box-header">
				<h2 class="box-title">Add a new event type</h2>
			</div><!-- /.box-header -->
			<div class="box-body">
				<form action="editeventtype.php?method=new" method="post" id="addSkill">
				<fieldset>
					<div class="form-group">
						<label for="name">Name:</label>
						<input class="form-control" id="name" name="name" type="text" placeholder="Morning Service" />
					</div>
					<div class="form-group">
						<label for="description">Description:</label>
						<input class="form-control" id="description" name="description" type="text" placeholder="A family service every Sunday at..." />
					</div>
					<div class="form-group">
						<label for="defaultDay">Default Day:</label>
						<input class="form-control" id="defaultDay" name="defaultDay" type="text" placeholder="Sunday" />
					</div>
					<div class="form-group">
						<label for="defaultTime">Default Time:</label>
						<input class="form-control" id="defaultTime" name="defaultTime" type="text" placeholder="10:00 AM" />
					</div>
					<div class="form-group">
						<label for="defaultRepitition">Default Repitition:</label>
						<input class="form-control" id="defaultRepitition" name="defaultRepitition" type="text" placeholder="???" />
					</div>
					<div class="form-group">
						<label for="defaultLocation">Default Location:</label>
						<select name="defaultLocation" id="defaultLocation" class="form-control">
							<option value="">No default location</option>
							<?php $locations = LocationQuery::create()->find();
                            foreach ($locations as $location) { ?>
									<option value="<?php echo $location->getId() ?>"><?php echo $location->getName() ?></option>
							<?php } ?>
						</select>
					</div>
				</div><!-- /.box-body -->
				<div class="box-footer">
					<input class="btn btn-primary" type="submit" value="Add" />
				</div><!-- /.box-footer -->

				</fieldset>
			</form>
		</div><!-- /.box -->
	</div><!-- /.col -->
</div><!-- /.row -->
<?php
if (isAdmin()) {
                                ?>
<div id="right">
		<div class="item"><a href="settings.php">Back to settings</a></div>
</div>
<?php
                            } ?>
<?php include 'includes/footer.php'; ?>
