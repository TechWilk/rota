<?php

namespace TechWilk\Rota;

/*
    This file is part of Church Rota.

    Copyright (C) 2015 Christopher Wilkinson

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

// Don't require session data, since verification code can take it's place
/*
if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
    // Just continue the code
}
else {
    header ("Location: login.php");
}*/

// Handle details from the header
$action = getQueryStringForKey('action');
$eventId = getQueryStringForKey('event');
$verify = getQueryStringForKey('verify');
$swapId = getQueryStringForKey('swap');

$eventId = filter_var($eventId, FILTER_SANITIZE_NUMBER_INT);
$swapId = filter_var($swapId, FILTER_SANITIZE_NUMBER_INT);

switch ($action) {
  case 'swap':
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $eventPersonId = $_POST['eventPerson'];
        $newUserRoleId = $_POST['newUserRole'];
        $eventPersonId = filter_var($eventPersonId, FILTER_SANITIZE_NUMBER_INT);
        $newUserRoleId = filter_var($newUserRoleId, FILTER_SANITIZE_NUMBER_INT);

        $swapId = createSwapEntry($eventPersonId, $newUserRoleId);

        header('Location: index.php');
    } else {
        $err = 'Swap details incorrect, please try again.';
    }
    break;
  case 'accept':
    if (canAcceptSwap($swapId) || $verify == verificationCodeForSwap($swapId)) {
        switch (acceptSwap($swapId)) {
        case '1':
          $message = 'Swap Successful';
          break;
        case '2':
          $message = 'Swap already accepted.';
          break;
        case '3':
          $message = 'Swap already declined.';
          break;
        case '4':
          $message = 'Swap already reverted.';
          break;
        default:
          $err = 'Technical issue - please inform system administrator';
          break;
      }
    } else {
        $err = 'Swap Already Actioned or Verification Code Invalid';
    }
    break;
  case 'decline':
    if (canDeclineSwap($swapId) || $verify == verificationCodeForSwap($swapId)) {
        switch (declineSwap($swapId)) {
        case '1':
          $message = 'Swap declined';
          break;
        case '2':
          $message = 'Swap already declined.';
          break;
        default:
          $err = 'Technical issue - please inform system administrator';
          break;
        }
    } else {
        $err = 'Swap Already Actioned or Verification Code Invalid';
    }
    break;

  default:
    // code...
    break;
}

if (!empty($eventId)) {
    // ensure user is logged in before allowing creation of swap
    if (!(isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true)) {
        $_SESSION['redirectUrl'] = siteSettings()->getSiteUrl().'/swap.php?event='.$eventId;
        header('Location: login.php');
    }
    $createSwap = true;

    $numberOfRoles = numberOfRolesOfUserAtEvent($sessionUserID, $eventId);

    if ($numberOfRoles > 0) {
        $roles = rolesOfUserAtEvent($sessionUserID, $eventId);
    } else {
        $roles = rolesUserCanCoverAtEvent($sessionUserID, $eventId);
    }
} elseif (!empty($swapId)) {
    $viewSwap = true;
    $swap = SwapQuery::create()->findPK($swapId);

    $statusText = 'Requested';
    $statusColour = 'primary';
    if ($swap->getAccepted()) {
        $statusText = 'Accepted';
        $statusColour = 'success';
    } elseif ($swap->getDeclined()) {
        $statusText = 'Declined';
        $statusColour = 'danger';
    }
}

    // ------ Presentation --------

$formatting = 'light';
include 'includes/header.php';
?>




	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>Swap Roles
				<small>Rotas</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Rotas</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
      
      <?php
      if (isset($message)) { ?>
      
        <p><?php echo $message ?></p>

      <?php } elseif (isset($err)) { ?>
      
        <p><strong>Error: </strong><?php echo $err ?></p>
      
      <?php } elseif (!empty($swap)) { ?>
        <?php
        $canAcceptSwap = canAcceptSwap($swap->getId());
        $canDeclineSwap = canDeclineSwap($swap->getId());
        ?>
        <?php $event = $swap->getEventPerson()->getEvent() ?>
        <div class="box box-<?php echo $statusColour ?>">
          <div class="box-header">
            <h2 class="box-title"><?php echo $statusText ?> Swap</h2>
          </div>
          <div class="box-body">
            <p><?php echo $event->getEventType()->getName() ?>: <?php echo $event->getName() ?> (<?php echo $event->getDate('d M Y') ?>)</p>
            <p>
              <strong>
                <s class="text-red">
                  <?php echo $swap->getOldUserRole()->getUser()->getFirstName().' '.$swap->getOldUserRole()->getUser()->getLastName() ?> (<?php echo $swap->getOldUserRole()->getRole()->getName() ?>)
                </s>
                &#8594;
                <span class="text-green">
                  <?php echo $swap->getNewUserRole()->getUser()->getFirstName().' '.$swap->getNewUserRole()->getUser()->getLastName() ?> (<?php echo $swap->getNewUserRole()->getRole()->getName() ?>)
                </span>
              </strong>
            </p>
            <?php echo $statusText == 'Requested' ? '<p>This swap is awaiting approval</p>' : '' ?>
          </div>
          <?php if ($canAcceptSwap || $canDeclineSwap) { ?>
          <div class="box-footer">
            <?php if ($canAcceptSwap) { ?>
            <a class="btn btn-primary" href="swap.php?action=accept&swap=<?php echo $swap->getId() ?>">Accept</a>
            <?php } ?>
            <?php if ($canDeclineSwap) { ?>
            <a class="btn" href="swap.php?action=decline&swap=<?php echo $swap->getId() ?>">Decline</a>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
        
      <?php } elseif ($numberOfRoles > 0) { ?>
            
        <?php foreach ($roles as $role) { ?>
        
        <form action="swap.php?action=swap" method="post" role="form">
          <div class="box box-primary">
            <div class="box-header">
              <h2 class="box-title"><?php echo roleNameFromId($role->roleId); ?></h2>
            </div>
            <div class="box-body">
              <p><strong>Swap from:</strong> <?php echo getNameWithId($sessionUserID); ?> (<?php echo roleNameFromId($role->roleId); ?>)</p>
              <input type="hidden" name="eventPerson" value="<?php echo $role->eventPersonId; ?>" />
              <div class="form-group">
                <label for="newUserRole">Swap To:</label>
                <select name="newUserRole" class="form-control">
                  <?php
                  if (roleCanSwapToOtherRoleInGroup($role->roleId)) {
                      $whereAnd = 'r.groupId = '.groupIdWithRole($role->roleId).' AND r.allowRoleSwaps IS NOT FALSE';
                  } else {
                      $whereAnd = 'r.id = '.$role->roleId;
                  }
                  $sql = 'SELECT ur.id, u.firstName, u.lastName, r.name FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN roles r ON r.id = ur.roleId WHERE u.id <> '.$role->userId.' AND '.$whereAnd.' ORDER BY lastName, firstName, r.name';
                  $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

                  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                      ?>
                    <option value='<?php echo $row['id']; ?>'><?php echo $row['firstName'].' '.$row['lastName'].' ('.$row['name'].')'; ?></option>
                  <?php
                  } ?>
                </select>
              </div>
            </div>
            <div class="box-footer">
              <input type="submit" value="Request Swap" class="btn btn-primary" />
            </div>
          </div>
        </form> 
        
        <?php } ?>

      <?php } elseif (count($roles) > 0) { ?>

        <?php foreach ($roles as $role) { ?>

        <form action="swap.php?action=swap" method="post" role="form">
          <div class="box box-primary">
            <div class="box-header">
              <h2 class="box-title"><?php echo roleNameFromId($role->roleId); ?></h2>
            </div>
            <div class="box-body">
              <input type="hidden" name="eventPerson" value="<?php echo $role->eventPersonId; ?>" />
              <p><strong>Offer to cover:</strong> <?php echo getNameWithId($role->userId); ?> (<?php echo roleNameFromId($role->roleId); ?>)</p>
              <input type="hidden" name="newUserRole" value="<?php echo $role->newUserRoleId; ?>" />
              <p><strong>Swap to:</strong> <?php echo getNameWithId($sessionUserID); ?> (<?php echo roleNameFromId($role->roleId); ?>)</p>
            </div>
            <div class="box-footer">
              <input type="submit" value="Offer to cover" class="btn btn-primary" />
            </div>
          </div>
        </form> 

        <?php } ?>
      
      <?php } else { ?>
      <?php // event has no roles the user can cover?>
      
      <div class="alert alert-warning">
        <h4><i class="icon fa fa-info"></i>There are no roles in the event you are skilled to cover.</h4>
        <p>If you need adding to the rota, speak to an admin.</p>
      </div>
      <?php } ?>


<?php include 'includes/footer.php'; ?>
