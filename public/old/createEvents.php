<?php namespace TechWilk\Rota;

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

if (!(isEventEditor() || isAdmin())) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

// ~~~~~~~~~~ PRESENTATION ~~~~~~~~~~

include 'includes/header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo $actionName ?> Event
			<small>Rotas</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Rotas</li>
		</ol>
	</section>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<form action="createEvent.php<?php if (isset($formaction)) {
    echo $formaction;
} ?>" method="post" id="createEvent" role="form">
			<!-- Left column -->
			<div class="col-md-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Event Details</h3>
					</div>

					<fieldset>
						<div class="box-body">
							<div class="col-md-6">

								<div class="form-group">
									<label for="eventName">Service name: <strong><?php if (isset($eventName) && (($hiddenForBandAdmin) || ($hiddenForEventEditor))) {
    echo $eventName;
} ?></strong></label>
									<input name="eventName" id="eventName" class="form-control" type="<?php if (($hiddenForBandAdmin) || ($hiddenForEventEditor)) {
    echo 'hidden';
} else {
    echo 'text';
}?>" value="<?php if (isset($eventName)) {
    echo $eventName;
} ?>" placeholder="Enter service name" />
								</div>
								<!-- /.form-group -->
								
								<div class="form-group">
									<label for="type">Type: <strong><?php if (isset($typename) && ($hiddenForBandAdmin)) {
    echo $typename;
} ?></strong></label>
									<select name="type" id="type" class="form-control" <?php if ($hiddenForBandAdmin) {
    echo 'hidden';
} ?>>
										<option value="<?php if (isset($type)) {
    echo $type;
} ?>"><?php if (isset($typename)) {
    echo $typename;
} ?></option>
										<?php
                                        $sql = 'SELECT id, name, description, defaultTime, defaultLocationId FROM eventTypes ORDER BY name';
                                        $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
                                        while ($ob = mysqli_fetch_object($result)) {
                                            if (!(isset($type) && $ob->id == $type)) {
                                                $defaultTime = strftime('%H:%M', strtotime($ob->defaultTime));
                                                echo "<option value='".$ob->id."' title='".$ob->description."' data-time='".($defaultTime == '00:00' ? '' : $defaultTime)."' data-location='".(is_null($ob->defaultLocationId) ? '' : $ob->defaultLocationId)."'>".$ob->name.'</option>';
                                            }
                                        } ?>
									</select>
								</div>
								<!-- /.form-group -->

								<div class="form-group">
									<label for="subType">Sub-type: <strong><?php if (isset($subtypename) && ($hiddenForBandAdmin)) {
                                            echo $subtypename;
                                        } ?></strong></label>
									<select name="subType" id="subType" class="form-control" <?php if ($hiddenForBandAdmin) {
                                            echo 'hidden';
                                        } ?>>
										<option value="<?php if (isset($subtype)) {
                                            echo $subtype;
                                        } ?>"><?php if (isset($subtypename)) {
                                            echo $subtypename;
                                        } ?></option>
										<?php
                                        $sql = 'SELECT * FROM eventSubTypes ORDER BY name';
                                        $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

                                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            if (isset($subtype) && $row['id'] == $subtype) {
                                            } else {
                                                echo "<option value='".$row['id']."' title='".$row['description']."'>".$row['name'].'</option>';
                                            }
                                        } ?>
									</select>
								</div>
								<!-- /.form-group -->
							</div>
							<div class="col-md-6">

								<div class="form-group">
									<label for="location">Location: <strong><?php if (isset($locationname) && ($hiddenForBandAdmin)) {
                                            echo $locationname;
                                        } ?></strong></label>
									<select  class="form-control" name="location" id="location" <?php if ($hiddenForBandAdmin) {
                                            echo 'hidden';
                                        } ?>>
										<option value="<?php if (isset($location)) {
                                            echo $location;
                                        } ?>"><?php if (isset($locationname)) {
                                            echo $locationname;
                                        } ?></option>
										<?php
                                        $sql = 'SELECT * FROM locations order by name';
                                        $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

                                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            if (isset($location) && $row['id'] == $location) {
                                            } else {
                                                echo "<option value='".$row['id']."'>".$row['name'].'</option>';
                                            }
                                        } ?>
									</select>
								</div>
								<!-- /.form-group -->

								<div class="form-group">
									<label for="date">Date: <strong><?php if (isset($date) && (($hiddenForBandAdmin) || ($hiddenForEventEditor))) {
                                            echo $date;
                                        } ?></strong></label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
									<input name="date" id="date" class="form-control" type="<?php if (($hiddenForBandAdmin) || ($hiddenForEventEditor)) {
                                            echo 'hidden';
                                        } else {
                                            echo 'text';
                                        }?>" value="<?php if (isset($date)) {
                                            echo $date;
                                        } ?>" placeholder="dd/mm/yyyy" />
									</div>
									<!-- /.input group -->
								</div>
								<!-- /.form-group -->
								
									<div class="form-group">
									<label for="time">Time (24h): <strong><?php if (isset($time) && (($hiddenForBandAdmin) || ($hiddenForEventEditor))) {
                                            echo $time;
                                        } ?></strong></label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-clock-o"></i>
										</div>
									<input name="time" id="time" class="form-control" type="<?php if (($hiddenForBandAdmin) || ($hiddenForEventEditor)) {
                                            echo 'hidden';
                                        } else {
                                            echo 'text';
                                        }?>" value="<?php if (isset($time)) {
                                            echo $time;
                                        } ?>" placeholder="hh:mm" />
									</div>
									<!-- /.input group -->
								</div>
								<!-- /.form-group -->
							</div><!-- /.col -->

							<div class="form-group">
								<label for="comment">Notes: <?php if (isset($comment) && ($hiddenForBandAdmin)) {
                                            echo $comment;
                                        } ?></label>
								<textarea name="comment" class="mceNoEditor form-control" rows="3" <?php if ($hiddenForBandAdmin) {
                                            echo 'hidden';
                                        } ?>><?php if (isset($comment)) {
                                            echo $comment;
                                        } ?></textarea>
							</div>
							<!-- /.form-group -->

							</div><!-- /.box-body -->
						</div><!-- /.box -->
						
						
						
						<div class="box box-primary">
							<div class="box-header">
								<h3 class="box-title">Rehersal Details</h3>
							</div>
							<div class="box-body">

								<div class="form-group">
									<div class="checkbox">
										<label><input name="norehearsal" id="norehearsal" type="<?php if ($hiddenForEventEditor) {
                                            echo 'hidden';
                                        } else {
                                            echo 'checkbox';
                                        }?>" value="1"  <?php if (isset($norehearsal) && $norehearsal != 0) {
                                            echo 'checked="checked"';
                                        } else {
                                        } ?>  />
											Have this event without a rehearsal: <strong><?php if (isset($norehearsal) && ($hiddenForEventEditor)) {
                                            echo $norehearsal ? 'yes' : 'no';
                                        } ?></strong>
										</label>
									</div>
								</div>
								<!-- /.form-group -->

								<div class="form-group">
									<label for="rehearsalDate">Rehearsal Date: <strong><?php if (isset($rehearsalDate) && ($hiddenForEventEditor)) {
                                            echo $rehearsalDate;
                                        } ?></strong></label>
									<input name="rehearsalDate" id="rehearsalDate" class="form-control" type="<?php if ($hiddenForEventEditor) {
                                            echo 'hidden';
                                        } else {
                                            echo 'text';
                                        }?>" value="<?php if (isset($rehearsalDate)) {
                                            echo $rehearsalDate;
                                        } ?>" placeholder="yyyy-mm-dd hh:mm:ss" />
								</div>
								<!-- /.form-group -->

							</div><!-- /.box-body -->
						</div><!-- /.box -->
						
						
						
						<div class="box box-primary">
							<div class="box-header">
								<h3 class="box-title">Sermon Details</h3>
							</div>
							<div class="box-body">

							<div class="form-group">
								<label for="sermonTitle">Sermon title: <strong><?php if (isset($sermonTitle) && (($hiddenForBandAdmin) || ($hiddenForEventEditor))) {
                                            echo $sermonTitle;
                                        } ?></strong></label>
								<input name="sermonTitle" id="sermonTitle" class="form-control" type="<?php if (($hiddenForBandAdmin) || ($hiddenForEventEditor)) {
                                            echo 'hidden';
                                        } else {
                                            echo 'text';
                                        }?>" value="<?php if (isset($sermonTitle)) {
                                            echo $sermonTitle;
                                        } ?>" placeholder="Enter sermon title" />
							</div>
							<!-- /.form-group -->

							<div class="form-group">
								<label for="eventGroup">Sermon series: <strong><?php if (isset($eventGroupName) && ($hiddenForBandAdmin)) {
                                            echo $eventGroupName;
                                        } ?></strong></label>
								<select name="eventGroup" id="eventGroup" class="form-control" <?php if ($hiddenForBandAdmin) {
                                            echo 'hidden';
                                        } ?>>
									<option value="<?php echo isset($eventGroup) ? $eventGroup : '' ?>"><?php echo isset($eventGroupName) ? $eventGroupName : '' ?></option>
									<?php
                                    $sql = 'SELECT * FROM eventGroups WHERE archived = false ORDER BY name';
                                    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                        if (isset($type) && $row['id'] == $type) {
                                        } else {
                                            echo "<option value='".$row['id']."' title='".$row['description']."'>".$row['name'].'</option>';
                                        }
                                    } ?>
								</select>

								<p><a href="series.php" id="createSeriesButton">Add Series</a></p>
							</div>
							<!-- /.form-group -->

							<div class="form-group">
								<label for="bibleVerse">Reading: <strong><?php if (isset($bibleVerse) && (($hiddenForBandAdmin) || ($hiddenForEventEditor))) {
                                        echo $bibleVerse;
                                    } ?></strong></label>
								<input name="bibleVerse" id="bibleVerse" class="form-control" type="<?php if (($hiddenForBandAdmin) || ($hiddenForEventEditor)) {
                                        echo 'hidden';
                                    } else {
                                        echo 'text';
                                    }?>" value="<?php if (isset($bibleVerse)) {
                                        echo $bibleVerse;
                                    } ?>" placeholder="Enter in format: Ephesians 1:1-15" />
							</div>
							<!-- /.form-group -->

						</div>
						<!-- /.box-body -->

						<div class="box-footer">
							<input type="submit" class="btn btn-primary" value="<?php echo $actionName == 'Edit' ? 'Save changes' : 'Create event' ?>" />
						</div>
						<!-- /.box-footer -->
					</fieldset>
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col left -->
		</form>
	</div><!-- /.row -->

  <div id="createSeries" class="modal modal-primary fade" role="dialogue">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Create new series</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
							<label for="seriesName">Series Name:</label>
							<input name="seriesName" id="seriesName" class="form-control" type="text" placeholder="Name" />
						</div>
						<!-- /.form-group -->

						<div class="form-group">
							<label for="seriesDescription">Description:</label>
							<textarea name="seriesDescription" id="seriesDescription" class="mceNoEditor form-control" rows="3" placeholder="Description about series: what is it about, why are we studing it, etc."></textarea>
						</div>
						<!-- /.form-group -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-outline" data-dismiss="modal" id="createSeriesSubmitButton">Create Series</button>
        </div>
      </div>
    </div>
  </div><!-- /.createSeries -->


<?php include 'includes/footer.php'; ?>
