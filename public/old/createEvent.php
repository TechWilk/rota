<?php namespace TechWilk\Rota;

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

// Handle details from the header
$action = getQueryStringForKey('action');
$eventID = getQueryStringForKey('id');
$sessionUserID = $_SESSION['userid'];

$actionName = 'Create';
$userisBandAdmin = isBandAdmin($sessionUserID);
$userisEventEditor = isEventEditor($sessionUserID);

if (($userisBandAdmin) || ($userisEventEditor) || (isAdmin())) {
    // Just continue the code
} else {
    header('Location: error.php?no=100&page='.basename($_SERVER['SCRIPT_FILENAME']));
}

if ($userisBandAdmin) {
    $hiddenForBandAdmin = true;
} else {
    $hiddenForBandAdmin = false;
}

if ($userisEventEditor) {
    $hiddenForEventEditor = true;
} else {
    $hiddenForEventEditor = false;
}

if ($action == 'edit' || $action == 'copy') {
    $sql = "SELECT *,
	(SELECT name FROM cr_locations WHERE cr_locations.id = cr_events.location) AS locationname,
	(SELECT name FROM cr_eventTypes WHERE cr_eventTypes.id = cr_events.type) AS typename,
	(SELECT name FROM cr_eventSubTypes WHERE cr_eventSubTypes.id = cr_events.subType) AS subtypename,
	(SELECT name FROM cr_eventGroups WHERE cr_eventGroups.id = cr_events.eventGroup) AS groupname
	FROM cr_events WHERE id = '$eventID'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if ($action == 'edit') {
            $id = $row['id'];
        }
        $date = $row['date'];
        $rehearsalDate = $row['rehearsalDate'];
        $type = $row['type'];
        $typename = $row['typename'];
        $subtypename = $row['subtypename'];
        $subtype = $row['subType'];
        $location = $row['location'];
        $locationname = $row['locationname'];
        if ($action == 'edit') {
            $formaction = '?action=edit&id='.$id;
        } else {
            $formaction = '';
        }
        $norehearsal = $row['rehearsal'];
        $comment = $row['comment'];
        $eventName = $row['name'];
        $eventGroup = $row['eventGroup'];
        $eventGroupName = $row['groupname'];
        $sermonTitle = $row['sermonTitle'];
        $bibleVerse = $row['bibleVerse'];
    }

    // format date
    $time = strftime('%H:%M', strtotime($date));
    $date = strftime('%d/%m/%Y', strtotime($date));

    if ($action == 'edit') {
        $actionName = 'Edit';
    }
}

// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $userRole = $_POST['userRole'];
    $rehersalDate = $_POST['rehearsalDate'];
    $location = $_POST['location'];
    $type = $_POST['type'];
    $subType = $_POST['subType'];
    $norehearsal = $_POST['norehearsal'];
    $eventGroup = $_POST['eventGroup'];
    $eventName = $_POST['eventName'];
    $bibleVerse = $_POST['bibleVerse'];
    $sermonTitle = $_POST['sermonTitle'];
    $comment = $_POST['comment'];

    //$date = $date;
    //$userRole = $userRole;
    //$rehersalDate = $rehersalDate;
    $location = mysqli_real_escape_string(db(), $location);
    $type = filter_var($type, FILTER_SANITIZE_NUMBER_INT);
    $subType = filter_var($subType, FILTER_SANITIZE_NUMBER_INT);
    $norehearsal = filter_var($norehearsal, FILTER_SANITIZE_NUMBER_INT);
    $eventGroup = filter_var($eventGroup, FILTER_SANITIZE_NUMBER_INT);
    $eventName = mysqli_real_escape_string(db(), trim($eventName));
    $bibleVerse = mysqli_real_escape_string(db(), trim($bibleVerse));
    $comment = mysqli_real_escape_string(db(), trim($comment));
    $sermonTitle = mysqli_real_escape_string(db(), trim($sermonTitle));

    if ($norehearsal) {
        $rehersalDate = '0000-00-00 00:00:00';
    }

    // convert format of date
    $date = str_replace('/', '-', $date); // ensure it isn't read as mm/dd/yyyy
    $date = strftime('%Y-%m-%d', strtotime($date.' 00:00:00'));
    $date = $date.' '.$time.':00';
    $date = mysqli_real_escape_string(db(), $date);

    if ($action == 'edit') {
        $sql = "UPDATE cr_events SET date = '$date', rehearsalDate = '$rehersalDate', location = '$location',
		rehearsal = '$norehearsal', type = '$type', subType = '$subType', name = '$eventName', eventGroup = '$eventGroup', sermonTitle = '$sermonTitle', bibleVerse = '$bibleVerse', comment = '$comment' WHERE id = '$id'";
        mysqli_query(db(), $sql) or die(mysqli_error(db()));
    } else {
        $sql = "INSERT INTO cr_events (date, createdBy, rehearsalDate, type, subType, location, rehearsal, name, eventGroup, sermonTitle, bibleVerse, comment)
		VALUES ('$date', '$sessionUserID','$rehersalDate', '$type', '$subType', '$location', '$norehearsal', '$eventName', '$eventGroup', '$sermonTitle', '$bibleVerse', '$comment')";
        mysqli_query(db(), $sql) or die(mysqli_error(db()));
        $id = mysqli_insert_id(db());
        $eventID = mysqli_insert_id(db());
    }

    // Band population
    if (isset($userRole)) {
        if ($action != 'edit') {
            //  This is if we are running through the first time, we just need to populate
            foreach ($userRole as $key => $userRoleValue) {
                addPeople($eventID, $userRoleValue);
            }
        } else {
            // If this is not the case, we need to compare two arrays. The first array is the people already there. The second array is submitted people

            // First array:
            $sql = "SELECT userRoleId FROM cr_eventPeople WHERE eventID = '$eventID'";
            //if ($userisBandAdmin) $sql = $sql . " and skillID in (select skillID from cr_skills where groupid=2)";
            //if ($userisEventEditor) $sql = $sql . " and skillID in (select skillID from cr_skills where groupid!=2)";

            //if ($userisBandAdmin) $sql = $sql . " AND userRoleId IN (SELECT id FROM cr_userRoles WHERE groupid IN (2,3,4))";
            //if ($userisEventEditor) $sql = $sql . " AND userRoleId IN (SELECT id FROM cr_userRoles WHERE NOT (groupid IN (2,3,4)))";

            $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                // We're going to put it all in a nice array called membersArray
                $membersArray[] = $row['userRoleId'];
            }

            // Compare the event one way to notice what's new
            if (!empty($membersArray)) {
                // Don't add empty things. Delete all members if there have been none sent back.

                $addarray = array_diff($userRole, $membersArray);
                while (list($key, $userRoleToAdd) = each($addarray)) {
                    addUserToEvent($eventID, $userRoleToAdd);
                }
                // Compare the other way to notice what's disappeared
                $deletearray = array_diff($membersArray, $userRole);

                while (list($key2, $userRoleToRemove) = each($deletearray)) {
                    removeUserFromEvent($eventID, $userRoleToRemove);
                }
            } else {
                // Don't add empty things.
                if (!empty($userRole)) {
                    $addarray = $userRole;
                    while (list($key, $userRoleToAdd) = each($addarray)) {
                        addUserToEvent($eventID, $userRoleToAdd);
                    }
                }
            }
        }
        // Otherwise there was nothing in userRole and we should just delete all people from the event
    } else {
        $delete_all_sql = "DELETE FROM cr_eventPeople WHERE eventID = '$eventID'";
        //if ($userisBandAdmin) $delete_all_sql = $delete_all_sql . " and skillID in (select skillID from cr_skills where groupid=2)";
        //if ($userisEventEditor) $delete_all_sql = $delete_all_sql . " and skillID in (select skillID from cr_skills where groupid!=2)";
        if ($userisBandAdmin) {
            $delete_all_sql = $delete_all_sql.' and skillID in (select skillID from cr_skills where groupid in (2,3,4))';
        }
        if ($userisEventEditor) {
            $delete_all_sql = $delete_all_sql.' and skillID in (select skillID from cr_skills where not (groupid in (2,3,4)))';
        }
        mysqli_query(db(), $delete_all_sql) or die(mysqli_error(db()));
    }

    // redirect
    if (isset($_SESSION['lastEventsFilter'])) {
        $type = $_SESSION['lastEventsFilter'];
        header('Location: events.php?view=all&filter='.$type.'#event'.$eventID);
    } else {
        header('Location: events.php?view=all#event'.$eventID);
    }
    exit;
}
$formatting = 'true';

$jsToInclude = ['createEvent'];

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
                                        $sql = 'SELECT id, name, description, defaultTime, defaultLocationId FROM cr_eventTypes ORDER BY name';
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
                                        $sql = 'SELECT * FROM cr_eventSubTypes ORDER BY name';
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
                                        $sql = 'SELECT * FROM cr_locations order by name';
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
							</div><!-- ./col -->

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
                                        }?>" value="1"  <?php if ((isset($norehearsal) && $norehearsal != 0) || !isset($rehearsalDate)) {
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
                                    $sql = 'SELECT * FROM cr_eventGroups WHERE archived = false ORDER BY name';
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

			<!-- Right column -->
			<div class="col-md-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Add people to the event:</h3>
					</div>
					<!-- /.box-header -->

					<fieldset>
						<div class="box-body">
						<?php
                            $usersInEvent = [];

                            try {
                                $userRolesInEvent = EventPersonQuery::create()->filterByEventId($eventID)->filterByRemoved('0')->find()->toArray(); //todo: ensure removed is not string
                                foreach ($userRolesInEvent as $ur) {
                                    $usersInEvent[] = $ur['UserRoleId'];
                                }
                            } catch (\Error $e) {
                                $usersInEvent = [];
                            }
                            $groups = GroupQuery::create()->joinWith('Group.Role')->joinWith('Role.UserRole')->find();

                            foreach ($groups as $group): ?>
							<legend><?php echo $group->getName() ?></legend>
							<?php foreach ($group->getRoles() as $role): ?>
								<div class="form-group col-md-6">
									<label><?php echo $role->getName() ?></label>
									<select name="userRole[]" multiple="multiple" class="form-control multi" style="width:100%;" data-placeholder="Select people for <?php echo $role->getName() ?>">
										<optgroup label="Regular">
										<?php $countReserve = 0 ?>
										<?php foreach ($role->getUserRoles() as $userRole): ?>
											<?php if (!$userRole->getReserve()): ?>
											<?php $isInEvent = in_array($userRole->getId(), $usersInEvent) ?>
											<option value="<?php echo $userRole->getId() ?>" <?php echo $isInEvent ? 'selected="selected"' : '' ?>><?php echo $userRole->getUser()->getFirstName().' '.$userRole->getUser()->getLastName() ?></option>
											<?php else: ?>
											<?php $countReserve += 1 ?>
											<?php endif //!userRole->getReserve?>
										<?php endforeach //users?>
										</optgroup>
										<?php if ($countReserve > 0): ?>
										<optgroup label="Reserve">
										<?php foreach ($role->getUserRoles() as $userRole): ?>
											<?php if ($userRole->getReserve()): ?>
											<?php $isInEvent = in_array($userRole->getId(), $usersInEvent) ?>
											<option value="<?php echo $userRole->getId() ?>" <?php echo $isInEvent ? 'selected="selected"' : '' ?>><?php echo $userRole->getUser()->getFirstName().' '.$userRole->getUser()->getLastName() ?></option>
											<?php endif ?>
										<?php endforeach //users?>
										</optgroup>
										<?php endif //countReserve > 0?>
									</select>
									</div>
								<?php endforeach // roles?>

							<?php endforeach // groups?>

							<?php /*

                            // find people in database for eventID
                            $sqlPeople = "SELECT *,
                            CONCAT(u.firstname, ' ', u.lastname) AS `name`,
                            g.name AS `group`,
                            r.description AS `role`,
                            ur.id AS `userRoleId`,
                            (SELECT userRoleId FROM cr_eventPeople ep WHERE ep.eventId = '$eventID' AND ep.userRoleId = ur.id	LIMIT 1) AS `inEvent`,
                            (SELECT COUNT(ep.id) FROM cr_eventPeople ep INNER JOIN cr_events e ON e.id = ep.eventId WHERE e.date > NOW() AND ur.id = ep.userRoleId AND e.id != '$eventID') AS numberOfUpcomingEvents
                            FROM cr_roles r
                            INNER JOIN cr_groups g ON g.id = r.groupId
                            INNER JOIN cr_userRoles ur ON r.id = ur.roleId
                            INNER JOIN cr_users u ON u.id = ur.userId
                            ORDER BY g.id, r.description, u.firstname, u.lastname";

                             */?>
						</div><!-- /.box-body -->
						<div class="box-footer">
							<input class="btn btn-primary" type="submit" value="<?php echo $actionName == 'Edit' ? 'Save changes' : 'Create event' ?>" />
						</div>
					</fieldset>
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col right -->
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
