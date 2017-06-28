<?php

session_start();

include_once "includes/config.php";
include_once "includes/functions.php";

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['userid'];

$user = UserQuery::create()->findPK($userId);

$userRoles = UserRoleQuery::create()->filterByUser($user)->find();

$sql = "SELECT
          DISTINCT e.id AS id,
          e.name AS eventName,
          e.comment AS comment,
          et.name AS eventType,
          et.id AS eventTypeId,
          est.name AS eventSubType,
          l.name AS eventLocation,
          e.sermonTitle,
          e.bibleVerse,
          eg.name AS eventGroup,
          DATE_FORMAT(e.date,'%m/%d/%Y %H:%i:%S') AS date,
          DATE_FORMAT(e.rehearsalDate,'%m/%d/%Y %H:%i:%S') AS rehearsalDateFormatted,
          ur.userId AS userID,
          group_concat(r.name SEPARATOR ', ') AS roles
        FROM cr_events e
          LEFT JOIN cr_eventTypes et ON e.type = et.id
          LEFT JOIN cr_eventGroups eg ON e.eventGroup = eg.id
          LEFT JOIN cr_eventSubTypes est ON e.subType = est.id
          LEFT JOIN cr_locations l ON e.location = l.id
          LEFT JOIN cr_eventPeople ep ON ep.eventId = e.id
          INNER JOIN cr_userRoles ur ON ep.userRoleId = ur.id
          INNER JOIN cr_roles r ON r.id = ur.roleId
        WHERE e.date >= DATE(NOW())
          AND ur.userId = '$userId'
          AND e.removed = 0
        GROUP BY e.id
        ORDER BY e.date
        ";

$result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

$month = "";
$userEvents = [];
while ($ob = mysqli_fetch_object($result)) {
    $userEvents[] = $ob;
}


$eventsThisWeek = EventQuery::create()->filterByDate(['min' => new DateTime(), 'max' => new DateTime('1 week')])->filterByRemoved(false)->orderByDate()->find();

if (isAdmin()) {
    $remainingEventsInGroups = GroupQuery::create()->find();
} elseif (isEventEditor()) {
    $remainingEventsInGroups = GroupQuery::create()->useRoleQuery()->filterByUserRole($userRoles)->endUse()->find();
}

$remainingEventsOfType = EventTypeQuery::create()->find();


if (isAdmin()) {
    $sql = "SELECT COUNT(id) AS pendingSwaps FROM cr_swaps WHERE accepted = 0 AND declined = 0";
    $results = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($results);
    
    $pendingSwaps = $ob->pendingSwaps;
}

$calendarTokens = CalendarTokenQuery::create()->filterByUser($user)->filterByRevoked(false)->find();

$twoWeeks = $interval = new DateInterval('P2W');
$dateInTwoWeeks = (new DateTime())->add($twoWeeks);
$lastEvent = EventQuery::create()->orderByDate('desc')->findOne();






// ~~~~~~~~~~ PRESENTATION ~~~~~~~~~~

include "includes/header.php";

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
    Dashboard
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/">Dashboard</a></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">


  <?php if (isset($_SESSION['notification'])): ?>
    <div class="alert alert-info alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-info"></i> Status</h4>
      <p><?php echo $_SESSION['notification'] ?></p>
    </div>
    <?php unset($_SESSION['notification']) ?>
  <?php endif; ?>


  <div class="row">

    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="ion ion-calendar"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">My Upcoming Events</span>
          <span class="info-box-number"><?php echo count($userEvents) ?> event<?php echo count($userEvents) == 1 ? '' : 's' ?></span>
          <a href="#my-events">view</a>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->

    <?php if (!empty($pendingSwaps) && $pendingSwaps > 0): ?>
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-<?php echo $pendingSwaps > 0 ? 'yellow' : 'aqua' ?>"><i class="ion ion-loop"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Pending swaps</span>
          <span class="info-box-number"><?php echo $pendingSwaps ?> pending swap<?php echo $pendingSwaps != 1 ? 's' : '' ?>.</span>
          <a href="swaps.php">view</a>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
    <?php endif; ?>

    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon
        <?php echo ($lastEvent->getDate()->getTimestamp() < $dateInTwoWeeks->getTimestamp()) ? ($lastEvent->getDate()->getTimestamp() < (new DateTime())->getTimestamp() ? 'bg-red' : 'bg-orange') : 'bg-aqua' ?>
        ">
          <i class="ion ion-information"></i>
        </span>
        <div class="info-box-content">
          <span class="info-box-text">Rota Ends</span>
          <span class="info-box-number"><?php echo timeInWordsWithTense($lastEvent->getDate()) ?></span>
          <?php if (isAdmin()): ?><a href="createEvent.php">add events</a><?php endif ?>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->

    
    <?php
    if ((isAdmin()) || ($logged_in_show_snapshot_button=='1')): ?>

    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
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

    <?php endif; /* END isAdmin() || logged_in_show_snapshot_button=='1' */ ?>

  </div><!-- /.row -->

  <div class="row">
    <div class="col-sm-8 col-md-8 col-lg-6">

      <?php
      // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      // ~~~~~~~~ Events this week ~~~~~~~~
      // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      ?> 

      <h2>Events this week:</h2>

      <?php if (count($eventsThisWeek) > 0): ?>

      <ul class="timeline" id="this-week">
        <?php foreach ($eventsThisWeek as $event): ?>

        <?php # Month separators
        $newMonth = $event->getDate('F Y');
        if ($month != $newMonth):
        $month = $newMonth;
        ?>
        <li class="time-label"><span class="bg-green"><?php echo $month ?></span></li>
        <?php endif ?>

        <li>
          <i class="fa fa-bell bg-blue"></i>
          <div class="timeline-item" id="event<?php echo $event->getId() ?>">
            <span class="time"><i class="fa fa-calendar-o"></i> <?php echo strftime(siteSettings()->getDayOnlyFormat(), $event->getDate()->getTimestamp()) ?> <i class="fa fa-clock-o"></i> <?php echo strftime(siteSettings()->getTimeOnlyFormat(), $event->getDate()->getTimestamp()) ?></span>
            <div class="timeline-header">
              <a href="event.php?id=<?php echo $event->getId() ?>">
                <h4><?php
                  echo $event->getDate('jS: ');
                  $eventPeople = EventPersonQuery::create()->filterByEvent($event)->find();
                  $roles = [];
                  foreach ($eventPeople as $eventPerson) {
                      if ($eventPerson->getUserRole()->getUserId() == $user->getId()) {
                          $roles[] = $eventPerson->getUserRole()->getRole()->getName();
                      }
                  }
                  $roles = implode(', ', $roles);
                  echo $roles != '' ? $roles : 'No involvement';
                  ?>
                </h4>
              </a>
              <a class="label label-default" href="events.php?view=all&filter=<?php echo $event->getEventTypeId() ?>"><?php echo $event->getEventType()->getName() ?></a>
            </div><!-- /.timeline-header -->

            <div class="timeline-body">
              <p><strong><?php echo $event->getEventGroup() ? $event->getEventGroup()->getName().': ' : '' ?></strong><?php echo $event->getSermonTitle() ?> <?php echo $event->getBibleVerse() ? '('.$event->getBibleVerse().')' : "" ?></p>
              <p><strong>Location:</strong> <?php echo $event->getLocation()->getName(); ?></p>
              
              <?php if ($event->getComment() != ""): ?>
              <blockquote>
                <p>
                  <?php echo $event->getComment() ?>
                </p>
                <small>Comments</small>
              </blockquote>
              <?php endif ?>
            </div><!-- /.box-body -->

            <div class="box-footer">
              <div class="btn-group">
                <button type='button' class='btn btn-success' data-toggle='modal' data-target='#peopleModal<?php echo $event->getId() ?>'>
                  <i class="fa fa-eye"></i>&nbsp; View more
                </button>
                <a href="swap.php?event=<?php echo $event->getId() ?>" class="btn btn-warning">
                  <i class="fa fa-refresh"></i>&nbsp; Swap
                </a>
                <?php if (isAdmin()): ?>
                <a class="btn btn-primary" href="createEvent.php?action=edit&id=<?php echo $event->getId() ?>">
                  <i class="fa fa-pencil"></i><span> &nbsp;Edit</span>
                </a>
                <a class="btn btn-primary" href="emailEvent.php?event=<?php echo $event->getId() ?>">
                  <i class='fa fa-envelope-o'></i><span> &nbsp;Send email</span>
                </a>
                <?php endif ?>
              </div><!-- /.btn-group -->
            </div>
          </div>
        </li>

        <div id="peopleModal<?php echo $event->getId(); ?>" class="modal fade" role="dialogue">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">
                    People involved on <?php echo $event->getDate('jS:') ?>
                  </h4>
                </div>
                <div class="modal-body">
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
                                WHERE ep.eventId = '".$event->getId()."'
                                  AND ep.removed = 0
                                ORDER BY g.name, r.name";

                  $resultPeople = mysqli_query(db(), $sqlPeople) or die(mysqli_error(db()));
                  $groupName = "";
                  $groupId = 0;
                  $identifier = "1";
                  $firstTime = true;

                  if (mysqli_num_rows($resultPeople) > 0):

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
                  else:
                    echo "<p>No roles assigned to this event.";
                  endif;
                ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                  <div class="btn-group">
                    <a href="event.php?id=<?php echo $event->getId() ?>" class="btn btn-success">
                      <i class="fa fa-eye"></i>&nbsp; View full details
                    </a>
                    <a href="swap.php?event=<?php echo $event->getId() ?>" class="btn btn-warning">
                      <i class="fa fa-refresh"></i>&nbsp; Swap
                    </a>
                    <?php if (isAdmin()): ?>
                    <a class="btn btn-primary" href="emailEvent.php?event=<?php echo $event->getId() ?>">
                      <i class='fa fa-envelope-o'></i><span> &nbsp;Send email</span>
                    </a>
                    <?php endif ?>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- /.peopleModal[id] -->
        <?php endforeach ?>
      </ul>
      <div class="timeline-load-more">
        <a href="events.php?view=all" class="btn btn-primary">view all events</a>
      </div>

      <?php else: ?>
      <p>There are no events this week. <a href="events.php?view=all">View all events</a></p>
      <?php endif ?>


      <?php
      // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      // ~~~~~~~~~ User's events ~~~~~~~~~~
      // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      ?>

      <h2>My events:</h2>

      <?php if (count($userEvents) > 0): ?>
      <?php $month = '' ?>

      <ul class="timeline" id="my-events">
        <?php foreach ($userEvents as $event): ?>

        <?php # Month separators
        $newMonth = strftime("%B %Y", strtotime($event->date));
        if ($month != $newMonth):
        $month = $newMonth;
        ?>
        <li class="time-label"><span class="bg-green"><?php echo $month ?></span></li>
        <?php endif ?>

        <li>
          <i class="fa fa-bell bg-blue"></i>
          <div class="timeline-item" id="event<?php echo $event->id ?>">
            <span class="time"><i class="fa fa-calendar-o"></i> <?php echo strftime(siteSettings()->getDayOnlyFormat(), strtotime($event->date)) ?> <i class="fa fa-clock-o"></i> <?php echo strftime(siteSettings()->getTimeOnlyFormat(), strtotime($event->date))?></span>
            <div class="timeline-header">
              <a href="event.php?id=<?php echo $event->id ?>">
                <h4><?php
                  echo date('jS: ', strtotime($event->date));
                  echo $event->roles;
                  ?>
                </h4>
              </a>
              <a class="label label-default" href="events.php?view=all&filter=<?php echo $event->eventTypeId ?>"><?php echo $event->eventType ?></a>
            </div><!-- /.timeline-header -->

            <div class="timeline-body">
              <p><strong><?php echo $event->eventGroup ? $event->eventGroup.': ' : '' ?></strong><?php echo $event->sermonTitle ?> <?php echo $event->bibleVerse ? '('.$event->bibleVerse.')' : "" ?></p>
              <p><strong>Location:</strong> <?php echo $event->eventLocation; ?></p>
              
              <?php if ($event->comment != ""): ?>
              <blockquote>
                <p>
                  <?php echo $event->comment ?>
                </p>
                <small>Comments</small>
              </blockquote>
              <?php endif ?>
            </div><!-- /.box-body -->

            <div class="box-footer">
              <div class="btn-group">
                <button type='button' class='btn btn-success' data-toggle='modal' data-target='#peopleModal<?php echo $event->id ?>'>
                  <i class="fa fa-eye"></i>&nbsp; View more
                </button>
                <a href="swap.php?event=<?php echo $event->id ?>" class="btn btn-warning">
                  <i class="fa fa-thumbs-o-down"></i>&nbsp; Can't Do It
                </a>
                <?php if (isAdmin()): ?>
                <a class="btn btn-primary" href="createEvent.php?action=edit&id=<?php echo $event->id ?>">
                  <i class="fa fa-pencil"></i><span> &nbsp;Edit</span>
                </a>
                <a class="btn btn-primary" href="emailEvent.php?event=<?php echo $event->id ?>">
                  <i class='fa fa-envelope-o'></i><span> &nbsp;Send email</span>
                </a>
                <?php endif ?>
              </div><!-- /.btn-group -->
            </div>
          </div>
        </li>

        <div id="peopleModal<?php echo $event->id; ?>" class="modal fade" role="dialogue">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">
                    People involved on
                    <?php
                    echo date('jS: ', strtotime($event->date));
                    ?>
                  </h4>
                </div>
                <div class="modal-body">
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
                                WHERE ep.eventId = '".$event->id."'
                                  AND ep.removed = 0
                                ORDER BY g.name, r.name";

                  $resultPeople = mysqli_query(db(), $sqlPeople) or die(mysqli_error(db()));
                  $groupName = "";
                  $groupId = 0;
                  $identifier = "1";
                  $firstTime = true;

                  if (mysqli_num_rows($resultPeople) > 0):

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
                  else:
                    echo "<p>No roles assigned to this event.";
                  endif;
                ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                  <div class="btn-group">
                    <a href="event.php?id=<?php echo $event->id ?>" class="btn btn-success">
                      <i class="fa fa-eye"></i>&nbsp; View full details
                    </a>
                    <a href="swap.php?event=<?php echo $event->id ?>" class="btn btn-warning">
                      <i class="fa fa-thumbs-o-down"></i>&nbsp; Can't Do It
                    </a>
                    <?php if (isAdmin()): ?>
                    <a class="btn btn-primary" href="emailEvent.php?event=<?php echo $event->id ?>">
                      <i class='fa fa-envelope-o'></i><span> &nbsp;Send email</span>
                    </a>
                    <?php endif ?>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- /.peopleModal[id] -->
        <?php endforeach ?>
      </ul>

      <?php else: ?>
      <p>You have no events on the upcoming rota. <a href="events.php">View all events</a></p>
      <?php endif ?>

    </div>


    <div class="col-sm-4 col-md-4 col-lg-6">

      <?php
      // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      // ~~~~~~~ Remaining events ~~~~~~~~~
      // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      ?>
      <?php if (isset($remainingEventsInGroups)): ?>

      <div class="row">
        <div class="col-lg-6">
          <div class="box box-solid">
            <div class="box-header">
              <h2 class="box-title">Last events for group</h2>
            </div>
            <div class="box-body">

              <?php foreach ($remainingEventsInGroups as $group): ?>

              <p>
                <?php echo $group->getName() ?>
                <?php $event = EventQuery::create()->useEventPersonQuery()->useUserRoleQuery()->useRoleQuery()->filterByGroup($group)->endUse()->endUse()->endUse()->orderByDate('desc')->findOne() ?>
                <?php if (!is_null($event)): ?>
                <span class="pull-right badge
                  <?php echo ($event->getDate()->getTimestamp() < $dateInTwoWeeks->getTimestamp()) ? ($event->getDate()->getTimestamp() < (new DateTime())->getTimestamp() ? 'bg-red' : 'bg-orange') : 'bg-green' ?>
                  ">
                  <?php echo timeInWordsWithTense($event->getDate()) ?>
                </span>
                <?php else: ?>
                <span class="pull-right badge bg-red">
                  never
                </span>
                <?php endif ?>
              </p>

              <?php endforeach ?>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="box box-solid">
            <div class="box-header">
              <h2 class="box-title">Last events of type</h2>
            </div>
            <div class="box-body no-padding">

              <ul class="nav nav-stacked">

              <?php foreach ($remainingEventsOfType as $type): ?>

                <?php $event = EventQuery::create()->filterByEventType($type)->orderByDate('desc')->findOne() ?>
                <?php if (!is_null($event) && $event->getDate() > new DateTime()): ?>
                <li>
                  <a href="events.php?view=all&filter=<?php echo $type->getId() ?>">
                    <?php echo $type->getName() ?>
                    <span class="pull-right badge
                      <?php echo ($event->getDate()->getTimestamp() < $dateInTwoWeeks->getTimestamp()) ? ($event->getDate()->getTimestamp() < (new DateTime())->getTimestamp() ? 'bg-red' : 'bg-orange') : 'bg-green' ?>
                      ">
                      <?php echo timeInWordsWithTense($event->getDate()) ?>
                    </span>
                  </a>
                </li>
                <?php endif ?>

              <?php endforeach ?>

              <?php if (count($remainingEventsOfType) <= 0): ?>
                <li>No upcoming events</li>
              <?php endif ?>

              </ul>

            </div>
          </div>
        </div>
      </div>

      <?php endif ?>


      <?php
      // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      // ~~~~~~~~~ User details ~~~~~~~~~~~
      // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      ?>  
    
      <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-yellow">
          <div class="widget-user-image">
            <img class="img-circle" src="<?php echo getProfileImageUrl($_SESSION["userid"], 'large') ?>" alt="User Avatar">
          </div>
          <!-- /.widget-user-image -->
          <h3 class="widget-user-username"><?php echo $user->getFirstName().' '.$user->getLastName() ?></h3>
          <h5 class="widget-user-desc">Account created <?php echo $user->getCreated("M. Y") ?></h5>
        </div>
        <div class="box-footer no-padding">
          <ul class="nav nav-stacked">
            <li><a href="addUser.php?action=edit"><?php echo $user->getEmail() ? $user->getEmail() : 'No email address' ?></a></li>
            <li><a href="addUser.php?action=edit">Roles:<span class="pull-right badge bg-aqua"><?php echo $userRoles->count() ?></span>
                <ul>
                  <?php echo $userRoles->count() > 0 ? '' : '<li>No roles</li>' ?>
                  <?php foreach ($userRoles as $userRole): ?>
                  <li>
                    <?php echo $userRole->getRole()->getGroup()->getName() ?>: <?php echo $userRole->getRole()->getName() ?>
                    <?php echo $userRole->getReserve() ? ' (<strong>reserve</strong>)' : '' ?>
                  </li>
                  <?php endforeach ?>
                </ul>
              </a>
            </li>
            <li>
              <a href="addUser.php?action=edit">
                <?php
                $userHasEmail = (strlen($user->getEmail()) > 3);
                $emailRemindersEnabled = ($userHasEmail && true); // todo: add opt-out for email reminders
                ?>
                Email Reminders <span class="pull-right badge bg-<?php echo $emailRemindersEnabled ? 'green' : 'red' ?>"><?php echo $emailRemindersEnabled ? siteSettings()->getDaysToAlert().' days before' : ($userHasEmail ? 'Disabled' : 'No email address') ?></span>
              </a>
            </li>
            <li>
              <a href="calendarTokens.php">Calendar Syncing <span class="pull-right badge bg-aqua"><?php echo $calendarTokens->count() ?></span></a>
            </li>
          </ul>
        </div>


      </div>


    </div>

  </div><!-- /.row -->


    
<?php
include "includes/footer.php";
?>
