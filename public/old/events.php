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

$sessionUserID = $_SESSION['userid'];
$userisBandAdmin = isBandAdmin($sessionUserID);
$userisEventEditor = isEventEditor($sessionUserID);

if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
    // continue code
} else {
    header('Location: login.php');
}

// Handle details from the header
$view = getQueryStringForKey('view');
$filter = getQueryStringForKey('filter');

// remember last filtering
if (isset($filter) && $filter != $_SESSION['lastEventsFilter']) {
    $_SESSION['lastEventsFilter'] = $filter;
} elseif ($view == 'all') {
    unset($_SESSION['lastEventsFilter']);
} else {
    $urlFilters = '';
    if (isset($_SESSION['lastEventsFilter'])) {
        $urlFilters = '&filter='.$_SESSION['lastEventsFilter'];
    }
    header('Location: events.php?view=all'.$urlFilters);
    exit;
}

// setup view
switch ($view) {
    case 'user':
        $_SESSION['onlyShowUserEvents'] = '1';
        break;
    case 'all':
        $_SESSION['onlyShowUserEvents'] = '0';
        break;
}

if (($_SESSION['onlyShowUserEvents'] == '1') && ($view == 'user' || $view == 'all') || empty($view)) {
    if ($sessionUserID == '' || !($sessionUserID > 0) || empty($sessionUserID)) {
        session_unset();
        session_destroy();
        header('Location: login.php');
    } else {
        header('Location: events.php?view='.$sessionUserID);
        exit;
    }
}

if (isAdmin()) {
    // Method to remove someone from the band
    /*

    if($skillremove == "true")
    {
        removeEventPeople($removeEventID, $removeSkillID);
        header ( "Location: index.php#section" . $removeEventID);
    }

    if($notifyEveryone == "true")
    {
        notifyEveryone($removeEventID);
        exit;
        header ( "Location: index.php#section" . $removeEventID);
    }

    if($notifyIndividual != "")
    {
        notifyIndividual($notifyIndividual, $removeEventID, $removeSkillID);
        header ( "Location: index.php#section" . $removeEventID);
    }*/
}

// If the form has been sent, we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $editeventID = $_GET['event'];
    $editskillID = $_POST['name'];
    $editbandID = $_POST['band'];

    if ($editskillID != '') {
        $sql = ("INSERT INTO eventPeople (eventId, userRoleId) VALUES ('$editeventID', '$editskillID')");
        if (!mysqli_query(db(), $sql)) {
            exit('Error: '.mysqli_error(db()));
        }

        // After we have inserted the data, we want to head back to the main page
        header('Location: index.php#section'.$editeventID);
        exit;
    }

    if ($editbandID != '') {
        $sqlbandMembers = "SELECT * FROM bandMembers WHERE bandID = '$editbandID'";
        $resultbandMembers = mysqli_query(db(), $sqlbandMembers) or exit(mysqli_error(db()));

        while ($bandMember = mysqli_fetch_object($resultbandMembers)) {
            $editskillID = $bandMember->skillID;

            $sql = ("INSERT INTO eventPeople (eventId, userRoleId) VALUES ('$editeventID', '$editskillID')");
            if (!mysqli_query(db(), $sql)) {
                exit('Error: '.mysqli_error(db()));
            }
        }

        // After we have inserted the data, we want to head back to the main page
        header('Location: index.php#section'.$editeventID);
        exit;
    }
}
?>

<?php $formatting = 'light';

// ------ Presentation --------

include 'includes/header.php';

if (siteSettings()->getEventSortingLatest() == 1) {
    $dateOrderBy = 'date desc';
} else {
    $dateOrderBy = 'date asc';
}

if (siteSettings()->getLoggedInShowSnapshotButton() == 1) {
    $logged_in_show_snapshot_button = '1';
} else {
    $logged_in_show_snapshot_button = '0';
}

if (isAdmin()) {
    $sql = 'SELECT COUNT(id) AS pendingSwaps FROM swaps WHERE accepted = 0 AND declined = 0';
    $results = mysqli_query(db(), $sql) or exit(mysqli_error(db()));
    $ob = mysqli_fetch_object($results);

    $pendingSwaps = $ob->pendingSwaps;
}
?>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				<?php // Vary page title depending on content showing
                if ($_SESSION['onlyShowUserEvents'] == '0' && $filter == '') {
                    echo 'All Events';
                } elseif ($filter != '') {
                    $mysqli_query = "SELECT DISTINCT name FROM eventTypes WHERE id = $filter";
                    $result = mysqli_query(db(), $mysqli_query) or exit(mysqli_error(db()));
                    $row = mysqli_fetch_object($result);

                    echo $row->name.'s';
                } elseif ($_SESSION['onlyShowUserEvents'] == '1') {
                    echo 'My Events';
                } else {
                    echo 'Events';
                } ?>
				<small>Rotas</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Events</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">


<style>
.timeline-item:target {
	background-color: #eee!important;
}
</style>

			<?php if (!empty($pendingSwaps) && $pendingSwaps > 0) {
                    if ($pendingSwaps > 1) {
                        $swapsMessage = $pendingSwaps.' swaps are pending approval. Emails have been sent to the people covering to approve the swaps.';
                    } else {
                        $swapsMessage = 'A swap is pending approval. An email has been sent to the person covering to approve the swap.';
                    } ?>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-info"></i> Swaps pending approval</h4>
					<p><?php echo $swapsMessage; ?> <a href="swaps.php">view <?php echo $pendingSwaps > 1 ? 'swaps' : 'swap' ?></a></p>
				</div>
			<?php
                } ?>

			<?php if (isset($_SESSION['notification'])) { ?>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-info"></i> Status</h4>
					<p><?php echo $_SESSION['notification'] ?></p>
				</div>
				<?php unset($_SESSION['notification']) ?>
			<?php } ?>

	<div class="col-sm-8 col-md-8 col-lg-6">

	<?php

    // fetch events from database, depending on filters

    if ($_SESSION['onlyShowUserEvents'] == '0' && $filter == '') {
        $sql = "SELECT
							e.id AS id,
							e.name AS eventName,
							et.name AS eventType,
							est.name AS eventSubType,
							l.name AS eventLocation,
							e.sermonTitle,
							e.bibleVerse,
							eg.name AS eventGroup,
							DATE_FORMAT(e.date,'%m/%d/%Y %H:%i:%S') AS sundayDate,
							DATE_FORMAT(e.rehearsalDate,'%m/%d/%Y %H:%i:%S') AS rehearsalDateFormatted
							FROM events e
							LEFT JOIN eventTypes et ON e.type = et.id
							LEFT JOIN eventGroups eg ON e.eventGroup = eg.id
							LEFT JOIN eventSubTypes est ON e.subType = est.id
							LEFT JOIN locations l ON e.location = l.id
							WHERE e.date >= DATE(NOW())
							AND e.removed = 0
							ORDER BY ".$dateOrderBy;
    } elseif ($filter != '') {
        $sql = "SELECT
							e.id AS id,
							e.name AS eventName,
							et.name AS eventType,
							est.name AS eventSubType,
							l.name AS eventLocation,
							e.sermonTitle,
							e.bibleVerse,
							eg.name AS eventGroup,
							DATE_FORMAT(e.date,'%m/%d/%Y %H:%i:%S') AS sundayDate,
							DATE_FORMAT(e.rehearsalDate,'%m/%d/%Y %H:%i:%S') AS rehearsalDateFormatted
							FROM events e
							LEFT JOIN eventTypes et ON e.type = et.id
							LEFT JOIN eventGroups eg ON e.eventGroup = eg.id
							LEFT JOIN eventSubTypes est ON e.subType = est.id
							LEFT JOIN locations l ON e.location = l.id
							WHERE e.type = '$filter'
							AND e.date >= DATE(NOW())
							AND e.removed = 0
							ORDER BY ".$dateOrderBy;
    } else {
        $sql = "SELECT
							DISTINCT e.id AS id,
							e.name AS eventName,
							et.name AS eventType,
							est.name AS eventSubType,
							l.name AS eventLocation,
							e.sermonTitle,
							e.bibleVerse,
							eg.name AS eventGroup,
							DATE_FORMAT(e.date,'%m/%d/%Y %H:%i:%S') AS sundayDate,
							DATE_FORMAT(e.rehearsalDate,'%m/%d/%Y %H:%i:%S') AS rehearsalDateFormatted,
							ur.userId AS userID


							FROM events e
							LEFT JOIN eventTypes et ON e.type = et.id
							LEFT JOIN eventGroups eg ON e.eventGroup = eg.id
							LEFT JOIN eventSubTypes est ON e.subType = est.id
							LEFT JOIN locations l ON e.location = l.id
							LEFT JOIN eventPeople ep ON ep.eventId = e.id
							INNER JOIN userRoles ur ON ep.userRoleId = ur.id
							INNER JOIN roles r ON r.id = ur.roleId
							WHERE e.date >= DATE(NOW())
							AND ur.userId = '$view'
							AND e.removed = 0
							ORDER BY ".$dateOrderBy;
    }
    $result = mysqli_query(db(), $sql) or exit(mysqli_error(db()));

    $month = ''; ?>
	<ul class="timeline">
<?php
    while ($row = mysqli_fetch_object($result)) {
        $eventID = $row->id;
        $eventName = $row->eventName;
        $type = $row->eventType;
        $subType = $row->eventSubType;

        // Month name
        setlocale(LC_TIME, siteSettings()->getLangLocale());
        $newMonth = strftime('%B %Y', strtotime($row->sundayDate));
        if ($month != $newMonth) {
            $month = $newMonth;
            echo '<li class="time-label"><span class="bg-green">'.$month.'</span></li>';
        }

        // Event details?>
		<li>
			<i class="fa fa-bell bg-blue"></i>
			<div class="timeline-item" id="event<?php echo $eventID; ?>">
				<?php /*<span class="label label-default"><?php echo $type ?></span> */?>
				<span class="time"><i class="fa fa-calendar-o"></i> <?php echo strftime(siteSettings()->getDayOnlyFormat(), strtotime($row->sundayDate)) ?> <i class="fa fa-clock-o"></i> <?php echo strftime(siteSettings()->getTimeOnlyFormat(), strtotime($row->sundayDate))?></span>
				<div class="timeline-header">
					<a href="event.php?id=<?php echo $eventID ?>">
						<h4><?php
                            echo date('jS: ', strtotime($row->sundayDate));
        if ($eventName != '') {
            echo '<strong>'.$eventName.'</strong><br />'.$subType.' - <em>'.$type.'</em>';
        } else {
            echo $subType.' - <em>'.$type.'</em>';
        } ?>
						</h4>
					</a>
				</div><!-- /.timeline-header -->
				<div class='timeline-body'>

				<div>

				<?php //<p><strong>Rehearsal:</strong> <?php
                //echo ($row->rehearsalDateFormatted = "0000-00-00 00:00:00") ? "none" : strftime(siteSettings()->time_format_normal,strtotime($row->rehearsalDateFormatted));</p>?>

				<p><strong><?php echo $row->eventGroup ? $row->eventGroup.': ' : '' ?></strong><?php echo $row->sermonTitle ?> <?php echo $row->bibleVerse ? '('.$row->bibleVerse.')' : '' ?></p>

				<p><strong>Location:</strong> <?php echo $row->eventLocation; ?></p>

				<div id="deleteModal<?php echo $eventID; ?>" class="modal modal-danger fade" role="dialogue">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title">Really delete event?</h4>
	      			</div>
							<div class="modal-body">
								<p>Are you sure you really want to delete the event taking place on <?php echo strftime(siteSettings()->getTimeFormatNormal(), strtotime($row->sundayDate)); ?>?<br />There is no way of undoing this action.</p>
							</div>
							<div class="modal-footer">
	              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
								<form action="event.php" method="POST">
									<input type="hidden" name="id" value="<?php echo $eventID ?>">
									<input type="hidden" name="action" value="delete">
									<button class="btn btn-outline">DELETE EVENT</button>
								</form>
							</div>
						</div>
					</div>
				</div><!-- /.deleteModal[id] -->
				</div>
				<div class="user-roles">
				<?php

                    $sqlPeople = "SELECT
												CONCAT(u.firstname, ' ', u.lastname) AS `name`,
												r.name AS `rolename`,
												ep.notified AS `notified`,
												g.id AS `group`,
												g.name AS `groupName`,
												(SELECT sw.id FROM swaps sw WHERE sw.accepted = 0 AND sw.declined = 0 AND sw.eventPersonId = ep.id ORDER BY sw.id DESC LIMIT 1) AS swap
												FROM userRoles ur
													INNER JOIN roles r ON r.id  = ur.roleId
													INNER JOIN groups g ON g.id = r.groupId
													INNER JOIN users u ON u.id = ur.userId
													INNER JOIN eventPeople ep ON ep.userRoleId = ur.id
												WHERE ep.eventId = '$eventID'
													AND ep.removed = 0
												ORDER BY g.name, r.name";

        $resultPeople = mysqli_query(db(), $sqlPeople) or exit(mysqli_error(db()));
        $groupName = '';
        $groupId = 0;
        $identifier = '1';
        $firstTime = true;

        if (mysqli_num_rows($resultPeople) > 0) {
            ?>
						<?php while ($viewPeople = mysqli_fetch_object($resultPeople)) {
                if ($viewPeople->group == $groupId) {
                    // Do nothing, because they are all in the same group
                } else {
                    // Update the group heading
                    $groupId = $viewPeople->group;
                    $groupName = $viewPeople->groupName;
                    if ($firstTime) {
                        $firstTime = false;
                    } else {
                        echo '</ul>';
                    }
                    echo '<p><strong>'.$groupName.'</strong></p>';
                    echo '<ul>';
                }

                echo '<li>';
                echo (isset($viewPeople->swap)) ? "<s><a class='text-danger' href='swap.php?swap=".$viewPeople->swap."'>" : '';
                echo $viewPeople->name;

                if ($viewPeople->rolename != '') {
                    echo ' - <em>'.$viewPeople->rolename.'</em>';
                } else {
                    // If there is no skill, we don't need to mention this.
                }
                echo (isset($viewPeople->swap)) ? '</a></s>' : '';

                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No roles assigned to this event.';
        } ?>
			</div><!-- /.user-roles -->
		</div><!-- /.box-body -->
		<div class="box-footer">
			<div class='btn-group'>
				<a href="swap.php?event=<?php echo $eventID; ?>" class="btn btn-warning">
					<?php // Vary button wording depending on content showing
                    if ($_SESSION['onlyShowUserEvents'] == '1') {
                        echo "<i class='fa fa-thumbs-o-down'></i>&nbsp; Can't Do It";
                    } else {
                        echo "<i class='fa fa-refresh'></i>&nbsp; Swap";
                    } ?>
				</a>

				<?php
                if (isAdmin() || $userisBandAdmin || $userisEventEditor) {
                    echo " <a class ='btn btn-primary' href='createEvent.php?action=edit&id=".$eventID."'><i class='fa fa-pencil'></i><span> &nbsp;Edit</span></a> "; /* Edit event */
                    echo " <a class ='btn btn-primary' href='createEvent.php?action=copy&id=".$eventID."'><i class='fa fa-pencil'></i><span> &nbsp;Copy</span></a> "; /* Edit event */
                    echo " <a class ='btn btn-primary' href='emailEvent.php?event=".$eventID."'><i class='fa fa-envelope-o'></i><span> &nbsp;Send email</span></a> "; /* Send email */
                }
        if (isAdmin()) {
            //echo "<a class='btn btn-primary' href='index.php?notifyEveryone=true&eventID=$eventID'><i class='fa fa-envelope-o'></i><span> &nbsp;Send email</span></a> "; /* Send email */

            echo "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-expanded='true'><span class='caret'></span></button>"; /* Menu dropdown */

            echo "<ul class='dropdown-menu'>";

            echo "<li><button type='button' class='btn btn-danger btn-block' data-toggle='modal' data-target='#deleteModal".$eventID."'>Delete</button></li>"; /* Delete Event */

            echo '</ul>';
        } ?>
			</div><!-- /.btn-group -->
		</div>
	</div>
</li>
	<?php
    } ?>
	</div><!-- /.row -->


	<div class="col-sm-4 col-md-4 col-lg-6">
	<!-- row of action buttons -->
			<div class="row">

				<?php if (isAdmin()) { ?>

				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<a href="createEvent.php">
		        <div class="info-box">
		          <span class="info-box-icon bg-aqua"><i class="ion ion-ios-plus-outline"></i></span>
		          <div class="info-box-content">
		            <span class="info-box-text">Create Event</span>
		            <span class="info-box-number">New Event</span>
		          </div><!-- /.info-box-content -->
		        </div><!-- /.info-box -->
					</a>
	      </div><!-- /.col -->

				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<a href="emailGroup.php">
		        <div class="info-box">
		          <span class="info-box-icon bg-aqua"><i class="ion ion-ios-email-outline"></i></span>
		          <div class="info-box-content">
		            <span class="info-box-text">Send Email</span>
		            <span class="info-box-number">Email Rota</span>
		          </div><!-- /.info-box-content -->
		        </div><!-- /.info-box -->
					</a>
	      </div><!-- /.col -->

				<?php } /* END isAdmin() */

                if ((isAdmin()) || ($logged_in_show_snapshot_button == '1')) { ?>

					<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
						<a href="tableView.php">
			        <div class="info-box">
			          <span class="info-box-icon bg-aqua"><i class="ion ion-drag"></i></span>
			          <div class="info-box-content">
			            <span class="info-box-text">View</span>
			            <span class="info-box-number">Table View</span>
			          </div><!-- /.info-box-content -->
			        </div><!-- /.info-box -->
						</a>
		      </div><!-- /.col -->

					<?php } /* END isAdmin() || logged_in_show_snapshot_button=='1' */ ?>

			</div><!-- /.row -->

			</div><!-- /.column -->

			<div class="row">


<?php include 'includes/footer.php'; ?>
