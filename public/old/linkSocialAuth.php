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

$action = $_GET['action'];

// ensure user is accessing correct data
if (isAdmin() && isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
} else {
    $id = $_SESSION['userid'];
}

// link or unlink
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ensure user is accessing correct data
    if (isAdmin() && isset($_POST['id'])) {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    } else {
        $id = $_SESSION['userid'];
    }

    $platform = strtolower($_POST['platform']);

    switch ($_POST['action']) {
    case 'link':
      switch ($platform) {
        case 'facebook':
          $_SESSION['fb-callback-url'] = 'fb-link.php';
          header('Location: fb-login.php');
          exit;
      }
      break;
    case 'unlink':
      removeSocialAuthFromUserWithId($id, $platform);
      createNotificationForUser($id, ucfirst($platform).' account unlinked', 'You have successfully unlinked your Facebook account.  Login via Facebook is now disabled for your account.  Feel free to relink your account at any time.', 'account', 'linkSocialAuth.php');
      break;

    default:
      // code...
      break;
  }
}

// list of possible social account links (names used as platform names in database)
$possibleAccounts = null;
if ($config['auth']['facebook']['enabled'] == true) {
    $possibleAccounts[] = 'Facebook';
}

// ----------- Presentation ------------

include 'includes/header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Link Social Account
			<small>Users</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="/users.php">Users</a></li>
			<li class="active">Edit Password</li>
		</ol>
	</section>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">

<?php if (empty($possibleAccounts)): ?>
  <div class="alert alert-warning">
    <h4><i class="icon fa fa-info"></i>Social login has not been enabled.</h4>
    <p>Speak with your site administrator if you think this feature should be enabled.</p>
  </div>
<?php endif; ?>

<?php foreach ($possibleAccounts as $platform): ?>
<div class="box box-primary">
  <div class="box-header">
    <h3 class="box-title"><?php echo $platform ?></h3>
  </div>
  <div class="box-body">
    <?php if (userIsLinkedToPlatform($id, $platform)): ?>
    <form action="#" method="post">
      <input type="hidden" name="id" value="<?php echo $id ?>" />
      <input type="hidden" name="platform" value="<?php echo $platform ?>" />
      <input type="hidden" name="action" value="unlink" />
      <button class="btn btn-danger">Unlink</button>
    </form>
    <?php else: ?>
    <form action="#" method="post">
      <input type="hidden" name="id" value="<?php echo $id ?>" />
      <input type="hidden" name="platform" value="<?php echo $platform ?>" />
      <input type="hidden" name="action" value="link" />
      <button class="btn btn-primary">Link</button>
    </form>
    <?php endif; ?>
  </div>
</div>
<?php endforeach; ?>
    

<?php include 'includes/footer.php';
