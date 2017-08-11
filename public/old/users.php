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
if (!isAdmin()) {
    header('Location: index.php');
    exit;
}

// Handle details from the header
$userrole = getQueryStringForKey('userrole');
$method = getQueryStringForKey('method');

$userrole = filter_var($userrole, FILTER_SANITIZE_NUMBER_INT);

// Method to remove a skill from someone
if ($method == 'delete' && $userrole) {
    removeUserRoleWithId($userrole);
    header('Location: users.php');
    exit;
}
if ($method == 'reserve' && $userrole) {
    setUserRoleReserveWithId($userrole);
    header('Location: users.php');
    exit;
}
if ($method == 'regular' && $userrole) {
    setUserRoleRegularWithId($userrole);
    header('Location: users.php');
    exit;
}
$formatting = 'light';
$hidefirst = true;
include 'includes/header.php';

$sql = 'SELECT COUNT(*) AS noOfUsers, (SELECT COUNT(*) FROM cr_users WHERE isAdmin = true) AS noOfAdmin FROM cr_users';
$result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
$noOfUsers = 0;
$noOfAdmin = 0;

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $noOfUsers = $row['noOfUsers'];
    $noOfAdmin = $row['noOfAdmin'];
}

$sql = 'SELECT g.name AS groupName, r.name AS roleName FROM cr_groups g INNER JOIN cr_roles r ON r.groupId = g.id ORDER BY g.name, r.name';
$result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

$groupName = '';
$first = true;
$roles = '';

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if ($groupName != $row['groupName']) {
        $groupName = $row['groupName'];
        if ($first) {
            $first = false;
        } else {
            $roles .= '</ul>';
        }
        $roles .= '<p><strong>'.$groupName.'</strong></p>';
        $roles .= '<ul>';
    }
    $roles .= '<li>'.$row['roleName'].'</li>';
}
$roles .= '</ul>';

$users = UserQuery::create()->orderByLastName()->orderByFirstName()->find();
$organisedUsers = [];
foreach ($users as $user) {
    $letter = strtolower(substr($user->getLastName(), 0, 1));
    $organisedUsers[$letter][] = $user;
}
?>





<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Users
      <small>Rotas</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Users</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
		
<div class="row">
	<div class="col-sm-6">

		<?php foreach ($organisedUsers as $initial => $usersWithInitial): ?>
		<div class="box box-solid">
			<div class="box-header">
				<h2 class="box-title"><?php echo strtoupper($initial) ?></h2>
			</div>
			<div class="box-body no-padding">
				<ul class="users-list clearfix">
					<?php foreach ($usersWithInitial as $user): ?>
					<li>
						<a class="js-no-link" href="addUser.php?action=edit&user=<?php echo $user->getId() ?>" data-toggle='modal' data-target='#user<?php echo $user->getId() ?>'>
							<img src="<?php echo getProfileImageUrl($user->getId(), 'large') ?>" alt="User Image">
							<span class="users-list-name"><?php echo $user->getFirstName().' '.$user->getLastName() ?></span>
						</a>
						<?php if (isAdmin($user->getId())): ?>
						<span class="label label-warning">Admin</span>
						<?php endif ?>
						<?php if (isBandAdmin($user->getId())): ?>
						<span class="label label-success">Band Admin</span>
						<?php endif ?>
						<?php if (isEventEditor($user->getId())): ?>
						<span class="label label-success">Event Editor</span>
						<?php endif ?>
						<span class="users-list-date">Last Login:</span>
						<span class="users-list-date"><?php echo timeAgoInWords($user->getLastLogin()) ?></span>
					</li>

					<div class="modal fade" id="user<?php echo $user->getId() ?>" role="dialogue">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
									<h4 class="modal-title">
										<?php echo $user->getFirstName().' '.$user->getLastName() ?>
										<?php if (isAdmin($user->getId())): ?>
										<span class="label label-warning">Admin</span>
										<?php endif ?>
										<?php if (isBandAdmin($user->getId())): ?>
										<span class="label label-success">Band Admin</span>
										<?php endif ?>
										<?php if (isEventEditor($user->getId())): ?>
										<span class="label label-success">Event Editor</span>
										<?php endif ?>
									</h4>
								</div>
								<div class="modal-body">
									<p><strong>Email address:</strong> <a href="<?php echo $user->getEmail() ? 'mailto:'.$user->getEmail() : '' ?>"><?php echo $user->getEmail() ? $user->getEmail() : 'none' ?></a></p>
									<p><strong>Mobile:</strong> <?php echo $user->getMobile() ? $user->getMobile() : 'none' ?></p>
									<p><strong>Roles:</strong></p>
									<?php $userRoles = $user->getUserRoles() ?>
									<ul>
										<?php echo $userRoles->count() > 0 ? '' : '<li class="text-red">'.$user->getFirstName().' has no roles</li>' ?>
										<?php foreach ($userRoles as $userRole): ?>
										<li>
											<?php echo $userRole->getRole()->getGroup()->getName() ?>: <?php echo $userRole->getRole()->getName() ?>
											<?php echo $userRole->getReserve() ? ' (<strong>reserve</strong>)' : '' ?>
										</li>
										<?php endforeach ?>
									</ul>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
									<a class="btn btn-link text-red" href="editPassword.php?id=<?php echo $user->getId(); ?>">Reset password</a>
									<a class="btn btn-primary" href="addUser.php?action=edit&user=<?php echo $user->getId() ?>">Edit user</a>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach ?>
        </ul>
			</div>
		</div>
		<?php endforeach ?>
			<?php /*

                    echo "<div class='isAdmin'>";
                    echo "<p><strong>Permissions:</strong><br />";
                    if($row['isAdmin'] == '1') { echo "Administrator<br />"; }
                    if($row['isBandAdmin'] == '1') { echo "Band Administrator<br />"; }
                    if($row['isEventEditor'] == '1') { echo "Event Editor<br />"; }
                    if($row['isOverviewRecipient'] == '1') { echo "Overview Recipient"; }
                    echo "</p></div>";?>
                </div>
            </div>
        <?php
        } */?>
	</div><!-- /.col -->

	<div class="col-sm-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h2 class="box-title">Manage users</h2>
			</div>
			<div class="box-body">
				<p>Users: <?php echo $noOfUsers; ?></p>
				<p>Admins: <?php echo $noOfAdmin; ?></p>
			</div><!-- /.box-body -->
			<div class="box-footer">
				<a href="addUser.php" class="btn btn-primary">Add user</a>
			</div>
		</div><!-- /.box -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h2 class="box-title">Available roles</h2>
			</div>
			<div class="box-body">
				<?php echo $roles; ?>
			</div><!-- /.box-body -->
			<div class="box-footer">
				<a href="roles.php" class="btn btn-primary">Edit roles</a>
			</div>
		</div><!-- /.box -->
	</div><!-- /.col -->
</div><!-- /.row -->


<?php include 'includes/footer.php'; ?>
