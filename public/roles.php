<?php
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
if (!isAdmin()) {
    header('Location: error.php?no=100&page='.basename($_SERVER['SCRIPT_FILENAME']));
    exit;
}

// Get the query string
$role = getQueryStringForKey('role');
$method = getQueryStringForKey('method');
$group = getQueryStringForKey('group');
$assignTo = getQueryStringForKey('assignto');

// sanitise inputs
$role = filter_var($role, FILTER_SANITIZE_NUMBER_INT);
$method = mysqli_real_escape_string(db(), $method);
$group = filter_var($group, FILTER_SANITIZE_NUMBER_INT);
$assignTo = filter_var($assignTo, FILTER_SANITIZE_NUMBER_INT);


// move roles between groups
if ($role && $assignTo) {
    $sql = "UPDATE cr_roles r SET r.groupId = '$assignTo' WHERE r.id = '$role'";
    if (!mysqli_query(db(), $sql)) {
        die('Error: ' . mysqli_error(db()));
    }
    header('Location: roles.php');
    exit;
}

// delete group
if ($method == 'delete' && $group) {
    removeGroup($group);
}

// delete role
if ($method == 'delete' && $role) {
    removeRole($role);
}


// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($method == 'newrole') {
        $groupId = $_POST['groups'];
        $groupId = filter_var($groupId, FILTER_SANITIZE_NUMBER_INT);

        $newrole = $_POST['newrole'];
        $newrole = mysqli_real_escape_string(db(), stripslashes($newrole));
    // reject imput if name not present
    if (empty($newrole)) {
        header('Location: roles.php');
        exit;
    }

        $rehersal = 0; // TODO: this needs completely redoing to accept multiple rehersals
        $rehersal = filter_var($rehersal, FILTER_SANITIZE_NUMBER_INT);

        $sql = "INSERT INTO cr_roles (name, description, rehersalId, groupId)
            VALUES ('$newrole', '$newrole', $rehersal, $groupId)";
        if (!mysqli_query(db(), $sql)) {
            die('Error: ' . mysqli_error(db()));
        }
    } elseif ($method == 'newgroup') {
        $groupId = $_POST['groups'];

        $newgroup = $_POST['newgroup'];
        $newgroup = mysqli_real_escape_string(db(), stripslashes($newgroup));
    // reject imput if name not present
    if (empty($newgroup)) {
        header('Location: roles.php');
        exit;
    }

        $sql = "INSERT INTO cr_groups (name, description)
            VALUES ('$newgroup', '$newgroup')";
        if (!mysqli_query(db(), $sql)) {
            die('Error: ' . mysqli_error(db()));
        }
    } else {
        // Handle renaming of the roles
        $roleId = $_POST['roleId'];
        $roleName = $_POST['roleName'];

        $formArray = array_combine($roleId, $roleName);


        while (list($id, $name) = each($formArray)) {
            updateRole($id, $name, $name);
        }
    }
        // After we have inserted the data, we want to head back to the main roles page
        header('Location: roles.php'); // Move to the home page of the admin section
    exit;
}




# --------- Functions ----------



// provide list of role groups in presentation
function listOfGroups($type = 'option', $roleId = '0')
{
    $sql = "SELECT *
          FROM cr_groups g
          ORDER BY g.id";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    $list = "";

    $i = 1;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if ($type == 'option') {
            $list = $list . "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
        } elseif ($type == 'li') {
            $list = $list . "<li>" . $row['name'] . "</li>";
        } elseif ($type == 'li-a') {
            $list = $list . "<li><a href='roles.php?role=" . $roleId . "&assignto=" . $row['id'] . "'>" . $row['name'] . "</a></li>";
        }
        $i++;
    }
    return $list;
}



# ------- Presentation --------



include('includes/header.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Roles
      <small>Settings</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>/settings.php">Settings</a></li>
      <li class="active">Roles</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
<!-- Left column -->
<div class="col-md-6">
  <div class="box box-primary">
    <div class="box-header">
  		<h2 class="box-title">Edit Roles / Change Groups</h2>
    </div>
    <div class="box-body">
          <form action="roles.php" method="post">
          <fieldset>
  		<?php
        $sql = "SELECT *,
                r.name AS roleName,
                r.description AS roleDescription,
                r.id AS roleId,
                g.name AS groupName,
                g.id as groupId
                FROM cr_groups g
                LEFT JOIN cr_roles r ON g.id = r.groupId
                ORDER BY g.id, r.name";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    $group = 0;
    echo "<div>";
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $roleId = $row['roleId'];
        if ($row['groupId'] == $group) {
            // Do nothing, because they are all in the same group
            $down = $group + 1;
            $up = $group - 1;
        } else {
            echo "</div>";
            // Update the group heading
        $groupname = $row['groupName'];
            $group = $row['groupId'];
            $down = $group + 1;
            $up = $group - 1;
            echo "<div><strong>" . $groupname . "</strong><br />";
        }
      // Print text input box if a role exists for the group.
      // Allows user to update role names and move roles between groups
      if ($roleId) {
          ?>
        <div class='input-group'>
    		  <input type="hidden" name="roleId[<?php echo $row['roleId'] ?>]" value="<?php echo $row['roleId'] ?>" />
          <input class='form-control' name='roleName[]' value="<?php echo $row['roleName'] ?>" maxlength="15" />
          <span class='input-group-addon'><a href='editRole.php?id=<?php echo $roleId ?>'><i class='fa fa-users'></i></a></span>
          <div class="input-group-btn">
            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Move to <span class="fa fa-caret-down"></span></button>
            <ul class="dropdown-menu">
              <?php echo listOfGroups('li-a', $roleId); ?>
              <li class="divider"></li>
              <li><a href="roles.php?method=delete&role=<?php echo $roleId; ?>">Delete role</a></li>
            </ul>
          </div>
        </div><!-- /.input-group -->
        <?php
      } else {
          echo "<p>No roles (<a href='roles.php?method=delete&group=$group'>Delete group</a>)</p>";
      }
    } ?>
    </div>
       </fieldset>
     </div><!-- /.box-body -->
     <div class="box-footer">
       <input class="btn btn-primary" type="submit" value="Update roles" /></p>
       </form>
     </div><!-- /.box-footer -->
   </div><!-- /.box -->
</div><!-- /.col -->

<!-- Right column -->
<div class="col-md-6">
  <div class="box box-primary">
    <div class="box-header">
  	 	<h2 class="box-title">Add a new role:</h2>
    </div><!-- /.box-header -->
    <div class="box-body">
  		<form action="roles.php?method=newrole" method="post" id="addRole">
  		<fieldset>
        <div class="form-group">
      		<label class="hidden" for="newrole">Role</label>
      		<input class="form-control" id="newrole" name="newrole" type="text" placeholder="Enter role name" maxlength="15" />
        </div>
        <div class="form-group">
          <label>Add to:</label>
          <select class="form-control" name="groups">
            <?php echo listOfGroups(); ?>
          </select>
        </div>
        <div class="checkbox">
    			<label for="rehearsal">
            <input name="rehearsal" id="rehearsal" type="checkbox" value="1"  />
            This role should attend rehearsals
          </label>

        </div>
      </div><!-- /.box-body -->
      <div class="box-footer">
        <input class="btn btn-primary" type="submit" value="Add new role" />
      </div><!-- /.box-footer -->
  		</fieldset>
  	</form>
  </div><!-- /.box -->

  <div class="box box-primary">
    <div class="box-header">
  	 	<h2 class="box-title">Add a new group:</h2>
    </div><!-- /.box-header -->
    <div class="box-body">
  		<form action="roles.php?method=newgroup" method="post" id="addGroup">
  		<fieldset>
        <div class="form-group">
      		<label class="hidden" for="newgroup">Group</label>
      		<input class="form-control" id="newgroup" name="newgroup" type="text" placeholder="Enter group name" maxlength="25"/>
        </div>
      </div><!-- /.box-body -->
      <div class="box-footer">
        <input class="btn btn-primary" type="submit" value="Add new group" />
      </div><!-- /.box-footer -->
  		</fieldset>
  	</form>
  </div><!-- /.box -->
</div><!-- /.col -->

<?php
if (isAdmin()) {
        ?>
<div class="row">
		<a class="btn" href="settings.php">Back to settings</a>
</div>
<?php
    } ?>
<?php include('includes/footer.php'); ?>
