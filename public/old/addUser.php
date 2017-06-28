<?php namespace TechWilk\Rota; use DateInterval; use DateTime;
// Include files, including the database connection
include('includes/config.php');
include('includes/functions.php');

// Start the session. This checks whether someone is logged in and if not redirects them
session_start();

if (! (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true)) {
    $_SESSION['redirectUrl'] = siteSettings()->getSiteUrl().'/addUser.php?'.$_SERVER["QUERY_STRING"];
    header('Location: login.php');
    exit;
}

$action = getQueryStringForKey('action');
$sessionUserID = $_SESSION['userid'];

if (isset($action) && $action != "create") {
    if (isAdmin() && !is_null(getQueryStringForKey('user'))) {
        $userId = getQueryStringForKey('user');
    } else {
        $userId = $sessionUserID;
    }
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);

    $user = UserQuery::create()->findPK($userId);
    $userRoles = UserRoleQuery::create()->filterByUser($user)->find();
}

// sanitise inputs
$sessionUserID = filter_var($sessionUserID, FILTER_SANITIZE_NUMBER_INT);


switch ($action) {
    case 'edit':
        // fetch details to populate form
        $sql = "SELECT * FROM cr_users WHERE id = '$userId'";
        $result = mysqli_query(db(), $sql) or die(mysqli_error);

        while ($row =  mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id = $row['id'];
            $firstname = $row['firstName'];
            $lastname = $row['lastName'];
            $email = $row['email'];
            $mobile = $row['mobile'];
            $userisAdmin = $row['isAdmin'];
            $userisBandAdmin = $row['isBandAdmin'];
            $userIsEventEditor = $row['isEventEditor'];
            $userIsOverviewRecipient = $row['isOverviewRecipient'];
        }
        break;

    case 'regular':
        $roleId = getQueryStringForKey('role');
        $roleId = filter_var($roleId, FILTER_SANITIZE_NUMBER_INT);
        $userRole = UserRoleQuery::create()->filterByUser($user)->filterByRoleId($roleId)->findOne();
        $userRole->setReserve(false);
        $userRole->save();
        header("Location: addUser.php?action=edit&user=".$user->getId());
        exit;

        break;

    case 'reserve':
        $roleId = getQueryStringForKey('role');
        $roleId = filter_var($roleId, FILTER_SANITIZE_NUMBER_INT);
        $userRole = UserRoleQuery::create()->filterByUser($user)->filterByRoleId($roleId)->findOne();
        $userRole->setReserve(true);
        $userRole->save();
        header("Location: addUser.php?action=edit&user=".$user->getId());
        exit;
        break;

    case 'remove':
        if (removeUser($userId)) {
            header('Location: users.php');
        }
        break;

    default:
        # code...
}



// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // sanitise inputs
    if (isAdmin()) {
        $firstname = $_POST['firstname'];
        $firstname = strip_tags($firstname);
        $lastname = $_POST['lastname'];
        $lastname = strip_tags($lastname);
    } else {
        $firstnameLower = strtolower($firstname);
        $lastnameLower = strtolower($lastname);
    }
    $email = $_POST['email'];
    $email = filter_var(strip_tags($email), FILTER_SANITIZE_EMAIL);

    $mobile = $_POST['mobile'];
    $mobile = strip_tags($mobile);

    $roles = $_POST['roles'];

  // sanitise checkbox
    $isAdminLocal = isset($_POST['isAdmin']) ? '1' : '0';
    $userIsOverviewRecipient = isset($_POST['isOverviewRecipient']) ? '1' : '0';
    $userisBandAdmin = isset($_POST['isBandAdmin']) ? '1' : '0';
    $userIsEventEditor = isset($_POST['isEventEditor']) ? '1' : '0';


    if ($action == "edit") {
        if (isAdmin()) {
            updateUser($userId, $firstname, $lastname, $email, $mobile);
            updatePermissions($userId, $isAdminLocal, $userisBandAdmin, $userIsEventEditor, $userIsOverviewRecipient);
        } else {
            updateUserContactDetails($userId, $email, $mobile);
        }
    } else {
        $password = RandomPassword(8, true, true, true);

        $userId = addUser($firstname, $lastname, $email, $mobile);
        updatePermissions($userId, $isAdminLocal, $userisBandAdmin, $userIsEventEditor, $userIsOverviewRecipient);
        forceChangePassword($userId, $password);

        $username = getUsernameWithId($userId);

        mailNewUser($userId, $password);
    }


    if (isAdmin()) {
        if (isset($band) or isset($roles)) {
            if (isset($roles)) {
                if ($action == "edit") {
                    //$sql2 = "SELECT *
                //	FROM cr_groups WHERE groupID != 2 AND groupID IN (SELECT groupID FROM cr_skills WHERE cr_skills.groupID = cr_groups.groupID AND cr_skills.userId = '$userId') ORDER BY groupID";

                $sql2 = "SELECT *,
								r.id AS roleId,
								r.name AS name,
								r.description AS description
								FROM cr_roles r
								LEFT JOIN cr_userRoles ur ON ur.roleId = r.id
								WHERE ur.userId = '$userId'";

                    $result2 = mysqli_query(db(), $sql2) or die(mysqli_error(db()));
                    while ($ob = mysqli_fetch_object($result2)) {
                        $existingRoles[] = $ob->roleId;
                    }
                
                    if (empty($existingRoles)) {
                        foreach ($roles as $role) {
                            addUserRole($userId, $role);
                        }
                    } else {
                        $addarray = array_diff($roles, $existingRoles);

                        foreach ($addarray as $role) {
                            addUserRole($userId, $role);
                        }

                        $deletearray = array_diff($existingRoles, $roles);

                        foreach ($deletearray as $role) {
                            if (! (EventQuery::create()->useEventPersonQuery()->useUserRoleQuery()->filterByRoleId($role)->endUse()->endUse()->count() > 0)) { // don't remove if role is used in event
                            removeUserRole($userId, $role);
                            }
                        }
                    }
                // Otherwise inserting from scratch
                } else {
                    foreach ($roles as $role) {
                        addUserRole($userId, $role);
                    }
                }
            }
        }
    }
    if (isAdmin()) {
        header("Location: users.php#section" . $userId);
        exit;
    }
}







// ~~~~~~~~~~ PRESENTATION ~~~~~~~~~~



include('includes/header.php');


// temp fix for issue with userId
if (isset($action) && $action != "create") {
    if (isAdmin() && !is_null(getQueryStringForKey('user'))) {
        $userId = getQueryStringForKey('user');
    } else {
        $userId = $sessionUserID;
    }
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);

    $user = UserQuery::create()->findPK($userId);
    $userRoles = UserRoleQuery::create()->filterByUser($user)->find();
} else {
    unset($userId);
}


?>

<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				<?php echo empty($action) ? "New" : ucfirst($action) ?>
				<small>User</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
				<li><a href="users.php">Users</a></li>
				<li class="active">Add/Edit</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php if ($action == 'edit') {
    ?>
      <div class="alert alert-info alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Don't forget to save</h4>
        Press the "save changes" button at the bottom of the page to save.
      </div>
			<?php
} else {
        ?>
				<div class="alert alert-info alert-dismissable">
	        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	        <h4><i class="icon fa fa-info"></i> Welcome email</h4>
	        Entering an email address will automatically send a welcome email and temporary password to the user.
	      </div>
			<?php
    } ?>


<div class="col-md-6">
  <div class="box box-primary">
    <div class="box-header">
      <h2 class="box-title">User Details</h2>
    </div>
    <?php
        // Work out what action we need to give the form
        if ($action == "edit") {
            $formstring = "user=$userId&action=$action";
        } else {
            $formstring = "user=$userId";
        }

    ?>
    <form action="addUser.php?<?php echo $formstring; ?>" method="post" id="addUser">
    		<fieldset>
          <div class="box-body">

    			<?php
          // if isAdmin() == false
          // ordered in this way to build the page with contact details before permissions
                if (!isAdmin()) {
                    if ($userId == $sessionUserID) {
                        echo $firstname . " " . $lastname;
                        $isCompromised=false;
                    } else {
                        notifyAttack(__FILE__, "Impersonating Attack", $sessionUserID);
                        $isCompromised=true;
                    }
                    $isCompromised=false;
                } else {
                    // if isAdmin() == true
                ?>
          <div class="form-group">
      			<label for="firstname">First name:</label>
      			<input class="form-control" name="firstname" id="firstname" type="text" value="<?php echo $firstname; ?>" placeholder="Enter first name" />
          </div>

          <div class="form-group">
      			<label for="lastname">Last name:</label>
      			<input class="form-control" name="lastname" id="lastname" type="text" value="<?php echo $lastname; ?>" placeholder="Enter last name" />
          </div>

          <?php
                } ?>
          <div class="form-group">
      			<label for="email" >Email:</label>
      			<input class="form-control" id="email" name="email" type="email" autocorrect="off" autocapitalize="none" value="<?php echo $email; ?>" placeholder="Enter their email address" />
          </div>

          <div class="form-group">
      			<label for="mobile">Mobile number:</label>
      			<input class="form-control" id="mobile" name="mobile" type="text" value="<?php echo $mobile; ?>" placeholder="Enter their mobile number" />
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->


      <div class="box box-primary">
        <div class="box-header">
          <h2 class="box-title">Permissions</h2>
        </div>

        <div class="box-body">

					<?php

          if (isAdmin()) {
              ?>

          <div class="checkbox">
      			<label for="isAdmin">
              <input name="isAdmin" id="isAdmin" type="checkbox" value="1" <?php if ($userisAdmin == '1') {
                  echo 'checked="checked"';
              } elseif ($userisAdmin == '0') {
              } ?> />
              Make them an ADMIN?:
            </label>
          </div>

          <div class="checkbox">
      			<label for="isBandAdmin">
              <input class="checkbox" name="isBandAdmin" id="isBandAdmin" type="checkbox" value="1" <?php if ($userisBandAdmin == '1') {
                  echo 'checked="checked"';
              } elseif ($userisBandAdmin == '0') {
              } ?> />
              Make them a BAND admin?:
            </label>
          </div>

          <div class="checkbox">
      			<label for="isEventEditor">
        			<input class="checkbox" name="isEventEditor" id="isEventEditor" type="checkbox" value="1" <?php if ($userIsEventEditor == '1') {
                  echo 'checked="checked"';
              } elseif ($userIsEventEditor == '0') {
              } ?> />
              Make them an EVENT EDITOR?:
            </label>
          </div>

					<hr />

					<?php
          } ?>

          <div class="checkbox">
      			<label for="isOverviewRecipient">
        			<input class="checkbox" <?php echo isAdmin() ? '' : 'disabled="disabled"' ?> name="isOverviewRecipient" id="isOverviewRecipient" type="checkbox" value="1" <?php if ($userIsOverviewRecipient == '1' || is_null($userIsOverviewRecipient)) {
              echo 'checked="checked"';
          } elseif ($userIsOverviewRecipient == '0') {
          }?> />
              Receive group emails?:
            </label>
          </div>

					<div class="checkbox">
      			<label for="isReminderRecipient">
        			<input class="checkbox" disabled= "disabled" name="isReminderRecipient" id="isReminderRecipient" type="checkbox" value="1" <?php if (true/*$userIsReminderRecipient == '1' || is_null($userIsReminderRecipient)*/) {
              echo 'checked="checked"';
          } elseif (true/*$userIsReminderRecipient*/ == '0') {
          }?> />
              Receive email reminders?: (optional in future update to system)
            </label>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->

  		</fieldset>
  		
        <div class="col-md-6">
          <div class="box box-primary">
          <fieldset>
            <div class="box-header">
              <h3 class="box-title">Roles:</h3>
            </div>
						<?php if (isAdmin()): ?>
            <div class="box-body">

          	<?php 

                    $usersRoles = [];
                    try {
                        foreach ($userRoles as $ur) {
                            $usersRoleIds[] = $ur->getRoleId();
                            $userRoles[$ur->getRoleId()] = $ur;
                        }
                    } catch (\Error $e) {
                        $usersRoleIds = [];
                    }
                    $groups = GroupQuery::create()->joinWith('Group.Role')->joinWith('Role.UserRole')->find(); //->where('UserRole.UserId = ?', $userId)->find();

                    foreach ($groups as $group): ?>
					<legend><?php echo $group->getName() ?></legend>
					<div class="form-group">
						<?php foreach ($group->getRoles() as $role): ?>
							<div class="checkbox">
								<label>
									<?php $userHasRole = in_array($role->getId(), $usersRoleIds) ?>
									<?php $roleIsUsed = (isset($userRoles[$role->getId()]) && EventQuery::create()->useEventPersonQuery()->filterByUserRole($userRoles[$role->getId()])->endUse()->count() > 0) ?>
									<?php $isReserve = (isset($userRoles[$role->getId()]) && $userRoles[$role->getId()]->getReserve()) ?>
									<input type="checkbox" id="role[<?php echo $role->getId() ?>]" name="roles[]" value="<?php echo $role->getId() ?>" <?php echo $userHasRole ? 'checked="checked"' : '' ?> <?php echo $roleIsUsed ? 'disabled="disabled"' : '' ?>/>
									<?php echo $role->getName() ?>
									<?php if ($isReserve): ?>
									(<strong>reserve</strong>)
									<?php endif ?>
									<?php if ($userHasRole): ?>
										<?php if ($isReserve): ?>
										<a href="addUser.php?action=regular&user=<?php echo $user->getId() ?>&role=<?php echo $role->getId() ?>" class="text-green">make regular</a>
										<?php else: ?>
										<a href="addUser.php?action=reserve&user=<?php echo $user->getId() ?>&role=<?php echo $role->getId() ?>" class="text-orange">make reserve</a>
										<?php endif ?>
									<?php endif ?>
								</label>
								</div>
							<?php endforeach // roles?>

						<?php endforeach // groups?>
					</div>
					<em>NOTE: You can only remove roles which have not been used in an event.</em>



					<?php
                    else: // if !isAdmin() - list all roles user has
                    ?>
					<div class="box-body">
						<ul>
							<?php echo $userRoles->count() > 0 ? '' : '<li>You have no roles</li>' ?>
							<?php foreach ($userRoles as $userRole): ?>
							<li>
								<?php echo $userRole->getRole()->getGroup()->getName() ?>: <?php echo $userRole->getRole()->getName() ?>
								<?php echo $userRole->getReserve() ? ' (<strong>reserve</strong>)' : '' ?>
							</li>
							<?php endforeach ?>
						</ul>
						<p><em>Contact the person in charge of your rota to alter your roles</em></p>
					</div><!-- /.box-body -->

					<?php endif; ?>

          </fieldset>
					<div class="box-footer">
  		<?php 
                if ($action == "edit") {
                    echo '<input class="btn btn-primary" type="submit" value="Save changes" />';
                } else {
                    echo '<input class="btn btn-primary" type="submit" value="Add user" />';
                }
         ?>
		</div><!-- /.box-footer -->
  	</form>
  </div>
</div>
<div id="right">
<?php if (isAdmin()) {
             ?>

		<div class="item"><a class="btn" href="users.php">View all users</a></div>
		<?php
         }
        
            if ($action == "edit") {
                ?>
				<div class="item">
					<a class="btn btn-danger" href="editPassword.php?id=<?php echo $userId; ?>">Change password</a>
					<a class="btn btn-primary" href="calendarTokens.php">Sync to calendar</a>
				</div>
			<?php
            }
        ?>
</div>
<?php include('includes/footer.php'); ?>
