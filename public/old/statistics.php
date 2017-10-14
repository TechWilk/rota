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
    header('Location: error.php?no=100&page='.basename($_SERVER['SCRIPT_FILENAME']));
    exit;
}

// Get the query string
$method = getQueryStringForKey('method');

// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($method == 'truncate') {
        $sql = "CREATE TABLE tmp_system_statistics as SELECT * from statistics WHERE type='system'";
        if (!mysqli_query(db(), $sql)) {
            die('Error: '.mysqli_error(db()));
        }

        $sql = ('TRUNCATE TABLE statistics');
        if (!mysqli_query(db(), $sql)) {
            die('Error: '.mysqli_error(db()));
        }

        $sql = ('ALTER TABLE statistics  AUTO_INCREMENT = 50');
        if (!mysqli_query(db(), $sql)) {
            die('Error: '.mysqli_error(db()));
        }

        $sql = 'INSERT INTO statistics (userid,date,type,detail1,detail2,detail3,script) ';
        $sql = $sql.'SELECT userid,date,type,detail1,detail2,detail3,script from tmp_system_statistics order by date';
        if (!mysqli_query(db(), $sql)) {
            die('Error: '.mysqli_error(db()));
        }

        $sql = 'DROP TABLE tmp_system_statistics';
        if (!mysqli_query(db(), $sql)) {
            die('Error: '.mysqli_error(db()));
        }

        insertStatistics('system', __FILE__, 'statistics deleted');

        // After we have truncated the data, we want to reload the page
        header('Location: statistics.php'); // Move to the home page of the admin section
        exit;
    } else {
    }
}

if ($method == 'showall') {
    $limit = ' ';
    $browserLimit = ' ';
} else {
    $limit = 'LIMIT 10';
    $browserLimit = 'LIMIT 5';
}

// ~~~~~~~~~~ Presentation ~~~~~~~~~~

include 'includes/header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
    Statistics
    <small>Settings</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?php echo siteSettings()->getSiteUrl() ?>/settings.php">Settings</a></li>
    <li class="active">Statistics</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
		<div>
		<?php
        if ($debug) {
            ?>
      <div class="box">
        <div class="box-content">
  				<table class='table table-hover'>
    				<thead>
      				<tr><th >Browser / Platform</th><th>Count</th></tr>
    				</thead>
    				<tbody>

              <?php

                        $sql = 'SELECT VERSION( ) AS mysqli_version';
            $result = mysqli_query(db(), $sql) or die('MySQL-Error: '.mysqli_error(db()));
            $dbv = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $mysqli_version = $dbv['mysqli_version'];

            if (substr($mysqli_version, 0, 1) == 5) {
                $sql = "SELECT getBrowserInfo(detail3) as browser,count(*) as count from statistics where detail1 like 'login%' and detail3!='' group by getBrowserInfo(detail3) order by count desc ".$browserLimit;
            } else {
                $sql = "SELECT detail3 as browser,count(*) as count from statistics where detail1 like 'login%' and detail3!='' group by detail3 order by count desc ".$browserLimit;
            }

            $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                extract($row);
                echo '<tr>';
                echo '<td>'.$browser.'</td>';
                echo '<td>'.$count.'</td>';
                echo '</tr>';
            } ?>

    				</tbody>
  				</table>
        </div>
      </div>
    <?php
        }
        ?>

    <div class="box">
      <div class="box-content">
    		<table class="table table-hover">
    		<thead>
    		<tr><th>Date</th><th>User</th><th>Type</th><th>Action</th><th>Info</th></tr>
    		</thead>
    		<tbody>
    		<?php
                $sql = "SELECT s.date,s.detail1,s.detail2,s.detail3,s.type,trim(concat(u.firstName,' ',u.lastName)) AS name FROM statistics s INNER JOIN users u ON u.id = s.userid";
          if (!isAdmin()) {
              $sql .= 'WHERE u.ID=s.userID';
              if (!$debug) {
                  $sql .= " AND s.type = 'system'";
              }
          }
                $sql .= ' ORDER BY date desc '.$limit;
                $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    extract($row);
                    echo '<tr>';
                    echo '<td>'.$date.'</td>';
                    echo '<td>'.$name.'</td>';
                    echo '<td>'.$type.'</td>';
                    echo '<td>'.$detail1.'</td>';
                    echo '<td>'.$detail2.'</td>';
                    //echo "<td>".$detail3."</td>";
                    echo '</tr>';
                }
            ?>
    		</tbody>
    		</table>
      </div>
      <div class="box-footer">
        <button data-toggle="modal" data-target="#truncStatData" class="btn btn-danger">Delete User Statistics</button>
      </div>
    </div>


	<div id="truncStatData" class="modal modal-danger fade" role="dialogue">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Really delete user statistics?</h4>
        </div>
        <div class="modal-body">
  				<p>Are you sure you really want to delete ALL user statistics data? <br>There is no way of undoing this action.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
          <form action="statistics.php?method=truncate" method="post" id="truncate">
          <button type="button" class="btn btn-outline" data-dismiss="modal" id="createSeriesSubmitButton">Delete statistics</button></form>
        </div>
      </div>
    </div>
	</div>


<?php
if (isAdmin()) {
                ?>
<div class="callout callout-info">
		<div class="item"><a href="settings.php">Back to settings</a></div>
		<?php	if ($method != 'showall') {
                    ?>
		<div class="item"><a href="statistics.php?method=showall">Show all statistics</a></div>
		<?php
                } else {
                    ?>
		<div class="item"><a href="statistics.php">Show latest statistics</a></div>
		<?php
                } ?>
</div>
<?php
            } ?>

<?php include 'includes/footer.php'; ?>
