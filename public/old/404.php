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

// ~~~~~~~~~~ PRESENTATION ~~~~~~~~~~

include 'includes/header.php';
?>




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Page Not Found</h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Page Not Found</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="box box-primary">
      <div class="box-header">
        <h2 class="box-title">Sorry, the page you are looking for can't be found.</h2>
      </div>
      <div class="box-body">
        <p>Please use the menu on the left, or return to the <a href="index.php">Dashboard</a></p>
      </div>
    </div>
      
<?php include 'includes/footer.php'; ?>