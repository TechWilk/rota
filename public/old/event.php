<?php namespace TechWilk\Rota; use DateInterval; use DateTime;

session_start();

include_once "includes/config.php";
include_once "includes/functions.php";

$eventId = getQueryStringForKey('id');
$eventId = filter_var($eventId, FILTER_SANITIZE_NUMBER_INT);

if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
    // Just continue the code
} else {
    $_SESSION['redirectUrl'] = siteSettings()->getSiteUrl().'/event.php?id='.$eventId;
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // remove event
    if ($_POST['action'] == 'delete') {
        $remove = $_POST['id'];
        $remove = filter_var($remove, FILTER_SANITIZE_NUMBER_INT);
        removeEvent($remove);
        header("Location: index.php");
        exit;
    }
}


$event = EventQuery::create()->findPK($eventId);







// ~~~~~~~~~~ PRESENTATION ~~~~~~~~~~

include "includes/header.php";

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
    <?php echo empty($event->getName()) ? "Event" : $event->getName() ?>
    <small>Events</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="events.php">Events</a></li>
    <li class="active"><?php echo empty($event->getName()) ? "Event" : $event->getName() ?></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">

	<div class="row">

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<h2 class="box-title">Event Details</h2>
				</div>
				<div class="box-body">
					<?php echo !empty($event->getName()) ? "<p><strong>Name:</strong> ".$event->getName()."</p>" : "" ?>
					<p><strong>Date:</strong> <?php echo $event->getDate('d/m/y'); ?></p>
					<p><strong>Location:</strong> <?php echo $event->getLocation()->getName() ?></p>
					<p><strong>Type:</strong> <?php echo $event->getEventType()->getName() ?></p>
					<p><strong>SubType:</strong> <?php echo $event->getEventSubType()->getName() ?></p>

					<?php if ($event->getComment()): ?>
					<blockquote>
						<p><?php echo $event->getComment(); ?></p>
					</blockquote>
					<?php endif ?>
				</div>
				<?php if (isAdmin()): ?>
				<div class="box-footer btn-group">
					<a class='btn btn-primary' href='createEvent.php?action=edit&id=<?php echo $event->getId() ?>'>Edit event</a>
					<a class='btn btn-primary' href='createEvent.php?action=copy&id=<?php echo $event->getId() ?>'>Copy event</a>
					<button type="button" class='btn btn-danger' data-toggle='modal' data-target='#deleteModal'>Delete</a>
				</div>
				<?php endif ?>
			</div>
			
			<?php if (isAdmin()): ?>
			<div id="deleteModal" class="modal modal-danger fade" role="dialogue">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Really delete event?</h4>
						</div>
						<div class="modal-body">
							<p>Are you sure you really want to delete this event?<br />There is no way of undoing this action.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
							<form action="#" method="POST">
								<input type="hidden" name="id" value="<?php echo $event->getId() ?>">
								<input type="hidden" name="action" value="delete">
								<button class="btn btn-outline">DELETE EVENT</button>
							</form>
						</div>
					</div>
				</div>
			</div><!-- /.deleteModal -->
			<?php endif ?>
			
			<div class="box box-primary">
				<div class="box-header">
					<h2 class="box-title">Sermon Details</h2>
				</div>
				<div class="box-body">
					<p><strong><?php echo $event->getEventGroup()->getName() ?>:</strong> <?php echo $event->getSermonTitle() ?></p>
					<p><strong>Reading:</strong> <?php echo $event->getBibleVerse() ?></p>
				</div>
			</div>
		</div><!-- /.col -->
		
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

			<div class="box box-primary">
				<div class="box-header">
					<h2 class="box-title">People</h2>
				</div>
				<div class="box-body">
						<?php

                            $sqlPeople = "SELECT
														CONCAT(u.firstname, ' ', u.lastname) AS `name`,
														r.name AS `rolename`,
														ep.notified AS `notified`,
														g.id AS `group`,
														g.name AS `groupName`,
														(SELECT sw.id FROM cr_swaps sw WHERE sw.accepted = 0 AND sw.declined = 0 AND sw.eventPersonId = ep.id ORDER BY sw.id DESC LIMIT 1) AS swap
														FROM cr_userRoles ur
															INNER JOIN cr_roles r ON r.id  = ur.roleId
															INNER JOIN cr_groups g ON g.id = r.groupId
															INNER JOIN cr_users u ON u.id = ur.userId
															INNER JOIN cr_eventPeople ep ON ep.userRoleId = ur.id
														WHERE ep.eventId = '$eventId'
															AND ep.removed = 0
														ORDER BY g.name, r.name";

                            $resultPeople = mysqli_query(db(), $sqlPeople) or die(mysqli_error(db()));
                            $groupName = "";
                            $groupId = 0;
                            $identifier = "1";
                            $firstTime = true;

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
                                    echo "</ul>";
                                }
                                echo "<p><strong>" . $groupName . "</strong></p>";
                                echo "<ul>";
                            }

                            echo "<li>";
                            echo (isset($viewPeople->swap)) ? "<s><a class='text-danger' href='swap.php?swap=".$viewPeople->swap."'>" : "";
                            echo $viewPeople->name;

                            if ($viewPeople->rolename != "") {
                                echo " - <em>" . $viewPeople->rolename . "</em>";
                            } else {
                                // If there is no skill, we don't need to mention this.
                            }
                            echo (isset($viewPeople->swap)) ? "</a></s>" : "";
                            
                            echo "</li>";
                        }
                        echo "</ul>";
                        ?>
					</div>
					<div class="box-footer">
						<a href="swap.php?event=<?php echo $event->getId() ?>" class="btn btn-warning">
							<i class="fa fa-refresh"></i>&nbsp; Swap
						</a>
					</div>
			</div>
			
			<!--div class="box box-primary">
				<div class="box-header">
					<h2 class="box-title">Comments</h2>
				</div>
				<div class="box-body">
					<p>Coming soon... <?php //echo $event->datetime?></p>
				</div>
			</div-->

			<div class="box">
				<div class="box-header">
					<h2 class="box-title">Changelog</h2>
				</div>
				<div class="box-body">
					<p>Created: <?php echo $event->getCreated('d/m/y') ?></p>
					<p>Modified: <?php echo $event->getUpdated('d/m/y') ?></p>
				</div>
			</div>
		</div><!-- /.col -->

	</div><!-- /.row -->
    
<?php
include "includes/footer.php";
?>
