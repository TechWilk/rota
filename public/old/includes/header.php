<?php namespace TechWilk\Rota;

use DateInterval;
use DateTime;

?><!DOCTYPE html>
<!--
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
-->

<html>
  <head>
    <meta charset="UTF-8">
    <title>Rotas | <?php echo $owner; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<!-- Select2 -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />


    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
		<?php $skin = siteSettings()->getSkin() ? siteSettings()->getSkin() : 'skin-blue-light' ?>
    <link rel="stylesheet" href="dist/css/skins/<?php echo $skin ?>.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


		<!-- Church Rota stuff
		<link rel="stylesheet" type="text/css" href="includes/style.css" />
		<link rel="stylesheet" type="text/css" href="includes/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="css/reveal.css">
		<script src="includes/jquery.js" language="javascript" type="text/javascript"></script>
		<script src="includes/jquery.reveal.js" type="text/javascript"></script> -->

	<script type="text/javascript">
/*
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-23120342-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
*/
</script>
	<?php if (isset($formatting) && $formatting == "true") {
    ?>
	<script src="includes/churchrota.js" language="javascript" type="text/javascript"></script>
    <script src="includes/jquery.jeditable.js" language="javascript" type="text/javascript"></script>
	<script src="includes/jquery-ui.js" language="javascript" type="text/javascript"></script>
	<script src="includes/timepicker.js" language="javascript" type="text/javascript"></script>


	<script src="includes/tiny_mce/tiny_mce.js" type="text/javascript"></script>
	<script type="text/javascript" >
		tinyMCE.init({
			mode : "textareas",
			editor_deselector : "mceNoEditor",
			theme : "simple"   //(n.b. no trailing comma, this will be critical as you experiment later)
		});

	$(document).ready(function() {
		$('#date').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'hh:mm:ss'
		});

		$('#accordion').accordion({active: false});

		$('#rehearsalDate').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'hh:mm:ss'
		});

		$('.edit_area').editable('<?php if (isset($sendurl)) {
        echo $sendurl;
    } ?>', {
			type : 'textarea',
			cancel    : 'Cancel',
        	 submit    : 'OK',
			 tooltip   : 'Click to edit',
			"submitdata": function () {
			return {
				editableaction: 'edit'
				};
			},
			callback : function(value, settings) {
         		window.location.reload();
   			}
		});
		$('.edit').editable('<?php if (isset($sendurl)) {
        echo $sendurl;
    } ?>', {
			"submitdata": function () {
			return {
				editableaction: 'edit',
				type: 'title'
				};
			},
			callback : function(value, settings) {
         		window.location.reload();
   			}
		});
	});
</script >
	<?php
} else {
        ?>
	<script src="includes/jquery.confirm.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="includes/jquery.confirm.css" />
	<script type="text/javascript">
		$(document).ready(function()
		{
  			$(".elementContent").hide();
			<?php if (isset($hidefirst) && $hidefirst != true) {
            ?>
			$(".elementContent:first").show()
			$('.elementHead:first').removeClass('arrowwaiting').addClass('arrowactive');
			<?php
        } ?>
  			$(".elementHead").click(function()
  			{
    			$(this).next(".elementContent").slideToggle(600);
				$(this).toggleClass("arrowwaiting",600);
				$(this).toggleClass("arrowactive",600);
  			});
			$('.elementHead').hover(function() {
 				$(this).css('cursor','pointer');
 				}, function() {
 				$(this).css('cursor','auto');
			});

			$('.delete').click(function(e) {

					e.preventDefault();
					thisHref	= $(this).attr('href');

					if($(this).next('div.question').length <= 0)
						$(this).after('<div class="question">Are you sure?<br/> <span class="yes">Yes</span><span class="cancel">Cancel</span></div>');

					$('.question').animate({opacity: 1}, 300);

					$('.yes').live('click', function(){
						window.location = thisHref;
					});

					$('.cancel').live('click', function(){
						$(this).parents('div.question').fadeOut(300, function() {
							$(this).remove();
						});
					});

				});

		});
		$(window).scroll(function() {
			if($(this).scrollTop() != 0) {
				$('#toTop').fadeIn();
			} else {
				$('#toTop').fadeOut();
			}
		});

	$('#toTop').click(function() {
		$('document').animate({ scrollTop:0 }, '1000');
		return false;
	});

	</script>
	<?php
    } ?>
</head>
<?php $skin = siteSettings()->getSkin() ? siteSettings()->getSkin() : 'skin-blue-light' ?>
<body class="<?php echo $skin ?> fixed">
	<div class="wrapper">

		<!-- Main Header -->
		<header class="main-header">

			<!-- Logo -->
			<a href="<?php echo siteSettings()->getSiteUrl() ?>" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini">Rota</span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg"><?php echo $owner; ?></span>
			</a>

			<!-- Header Navbar -->
			<nav class="navbar navbar-static-top" role="navigation">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<!-- Navbar Right Menu -->
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<!-- Messages: style can be found in dropdown.less-->
						<li class="dropdown messages-menu">
							<!-- Menu toggle button -->
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-envelope-o"></i>
								<?php //<span class="label label-success">4</span>?>
							</a>
							<ul class="dropdown-menu">
								<li class="header">All Saints email</li>
								<li>
									<!-- inner menu: contains the messages -->
									<ul class="menu">
										<li><!-- start message -->
											<a href="https://allsaints.church/mail">
												<div class="pull-left">
													<!-- User Image -->
													<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
												</div>
												<!-- Message title and timestamp -->
												<h4>
													Church Emails
													<?php //<small><i class="fa fa-clock-o"></i> 5 mins</small>?>
												</h4>
												<!-- The message -->
												<p>Login to your All Saints email account</p>
											</a>
										</li><!-- end message -->
										<li><!-- start message -->
											<a href="#">
												<div class="pull-left">
													<!-- User Image -->
													<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
												</div>
												<!-- Message title and timestamp -->
												<h4>
													COMING SOON: Rota Emails
													<?php //<small><i class="fa fa-clock-o"></i> 5 mins</small>?>
												</h4>
												<!-- The message -->
												<p>View your messages from the rota system</p>
											</a>
										</li><!-- end message -->
									</ul><!-- /.menu -->
								</li>
								<li class="footer"><a href="#">See All Messages</a></li>
							</ul>
						</li><!-- /.messages-menu -->

						<!-- Notifications Menu -->
						<?php
                        if (isset($_SESSION['userid'])):
                            $userId = $_SESSION['userid'];
                            $sql = "SELECT id, timestamp, summary, body, link, seen FROM cr_notifications WHERE userId = '$userId' AND dismissed = FALSE AND archived = FALSE ORDER BY id DESC";

                            $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

                            $unseen = 0;

                            while ($ob = mysqli_fetch_object($result)) {
                                $notifications[] = $ob;
                                if ($ob->seen == false) {
                                    $unseen += 1;
                                }
                            }
                        ?>
						<li class="dropdown notifications-menu">
							<!-- Menu toggle button -->
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bell-o"></i>
								<?php echo $unseen >= 1 ? "<span class=\"label label-warning\">".$unseen."</span>" : "" ?>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have <?php echo $unseen >= 1 ? $unseen : "no" ?> new notifications</li>
								<li>
									<!-- Inner Menu: contains the notifications -->
									<ul class="menu">
									<?php foreach ($notifications as $n): ?>
										<li><!-- start notification -->
											<a href="notification.php?click=notifications-panel&id=<?php echo $n->id ?>">
												<i class="fa fa-users text-aqua"></i> <?php echo $n->seen ? $n->summary : "<strong>".$n->summary."</strong>" ?>
												<small><?php echo timeAgoInWords($n->timestamp) ?></small>
											</a>
										</li><!-- end notification -->
									<?php endforeach; ?>
									</ul>
								</li>
								<li class="footer"><a href="#">View all (comming soon...)</a></li>
							</ul>
						</li>
						<?php
                        endif;

                        /*
                        <!-- Tasks Menu -->
                        <li class="dropdown tasks-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-flag-o"></i>
                                <span class="label label-danger">9</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 9 tasks</li>
                                <li>
                                    <!-- Inner menu: contains the tasks -->
                                    <ul class="menu">
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <!-- Task title and progress text -->
                                                <h3>
                                                    Design some buttons
                                                    <small class="pull-right">20%</small>
                                                </h3>
                                                <!-- The progress bar -->
                                                <div class="progress xs">
                                                    <!-- Change the css width attribute to simulate progress -->
                                                    <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">20% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li><!-- end task item -->
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">View all tasks</a>
                                </li>
                            </ul>
                        </li>
                        */

                        if (isset($_SESSION['userid'])): ?>
						<!-- User Account Menu -->
						<li class="dropdown user user-menu">
							<?php
                            $sql = "SELECT u.created FROM cr_users u WHERE id = ".$_SESSION["userid"];
                                            $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
                                            $currentUser = mysqli_fetch_object($result);
                            ?>
							<!-- Menu Toggle Button -->
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<!-- The user image in the navbar-->
								<img src="<?php echo getProfileImageUrl($_SESSION["userid"], 'small') ?>" class="user-image" alt="User Image">
								<!-- hidden-xs hides the username on small devices so only the image appears. -->
								<span class="hidden-xs"><?php echo $_SESSION['name']; ?></span>
							</a>
							<ul class="dropdown-menu">
								<!-- The user image in the menu -->
								<li class="user-header">
									<img src="<?php echo getProfileImageUrl($_SESSION["userid"], 'large') ?>" class="img-circle" alt="User Image">
									<p>
										<?php echo $_SESSION['name']; ?>
										<small>Account created <?php echo strftime("%b. %Y", strtotime($currentUser->created));
                                        ?></small>
									</p>
								</li>
								<!-- Menu Body -->
								<li class="user-body">
									<div class="col-xs-4 text-center">
										<a href="calendarTokens.php">Sync to calendar</a>
									</div>
									<div class="col-xs-4 text-center">
										<a href="editPassword.php">Change Password</a>
									</div>
									<div class="col-xs-4 text-center">
										<a href="linkSocialAuth.php">Social Login</a>
									</div>
								</li>
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
										<a href="addUser.php?action=edit" class="btn btn-default btn-flat">My Account</a>
									</div>
									<div class="pull-right">
										<a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
									</div>
								</li>
							</ul>
						</li>
						<?php endif; ?>

						<!-- Control Sidebar Toggle Button -->
						<li>
							<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
						</li>
					</ul>
				</div>
			</nav>
		</header>
		<!-- Left side column. contains the logo and sidebar -->
		<aside class="main-sidebar">

			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">

				<!-- Sidebar user panel (optional) -->
				<div class="user-panel">
					<div class="pull-left image">
						<img src="<?php echo getProfileImageUrl($_SESSION["userid"], 'large') ?>" class="img-circle" alt="User Image">
					</div>
					<div class="pull-left info">
						<p><?php echo $_SESSION['name']; ?></p>
						<!-- Status -->
						<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>

				<!-- search form (Optional) -->
				<form action="#" method="get" class="sidebar-form">
					<div class="input-group">
						<input type="text" name="q" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
							<!--button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button-->



						</span>
					</div>
				</form>
				<!-- /.search form -->

				<!-- Sidebar Menu -->
				<ul class="sidebar-menu">
					<li class="header">ROTAS</li>
					<li <?php echo(basename($_SERVER['SCRIPT_FILENAME'])=='index.php'? 'class="active"' : '');?>>
            <a href="index.php">
              <i class="fa fa-tachometer"></i> <span>Dashboard</span>
            </a>
          </li>
					<?php if (isLoggedIn()) {
                                            ?>
					<li class="treeview <?php echo(basename($_SERVER['SCRIPT_FILENAME'])=='events.php' || basename($_SERVER['SCRIPT_FILENAME'])=='createEvent.php' ? ' active' : ''); ?>">
						<a  href="events.php"><i class="fa fa-calendar"></i> <span>Services</span><i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">
							<?php /*<li><a href="events.php?view=user">My Events</a></li>*/ ?>
							<?php
                                $filter_sql = "SELECT id, name
								FROM cr_eventTypes
								WHERE id IN
									(SELECT `cr_events`.`type`
									FROM cr_events
									WHERE date >= DATE(NOW())
									AND cr_events.removed = 0)
								ORDER BY name";
                                            $result = mysqli_query(db(), $filter_sql) or die(mysqli_error(db()));

                                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                                ?>
									<li <?php
                                            if (!empty($filter)) {
                                                if ($filter == $row['id']) {
                                                    echo "class='active' ";
                                                }
                                            } ?>>
										<a href="events.php?view=all&filter=<?php echo $row['id']; ?>"><?php echo $row['name']; ?>s</a></li>
									<?php
                                            } ?>
								<li <?php echo empty($filter) && isset($view) && $view == 'all' ? 'class="active"' : '' ?>>
									<a href="events.php?view=all">View All</a>
								</li>
								<?php if (isAdmin()): ?>
								<li <?php echo(basename($_SERVER['SCRIPT_FILENAME'])=='createEvent.php' && is_null(getQueryStringForKey('id')) ? 'class="active"' : '') ?>>
									<a href="createEvent.php">Add Event</a>
								</li>
								<?php endif ?>
						</ul>
					</li>

					<li <?php echo(basename($_SERVER['SCRIPT_FILENAME'])=='resources.php'? 'class="active"' : ''); ?>>
            <a href="resources.php">
              <i class="fa fa-folder-o"></i> <span>Resources</span>
            </a>
          </li>
					<?php if (!isAdmin()) {
                                                ?>
            <li <?php echo(basename($_SERVER['SCRIPT_FILENAME'])=='addUser.php'? 'class="active"' : ''); ?>>
              <a href="addUser.php?action=edit">
                <i class="fa fa-user"></i> <span>My account</span>
              </a>
            </li>
            <?php
                                            } ?>
					<?php
                                        }
                    if (isAdmin()) {
                        ?>
  					<li <?php echo(basename($_SERVER['SCRIPT_FILENAME'])=='users.php'? 'class="active"' : '');
                        echo(basename($_SERVER['SCRIPT_FILENAME'])=='addUser.php'? 'class="active"' : ''); ?>>
          <a href="users.php">
            <i class="fa fa-users"></i> <span>Users</span>
          </a>
        </li>
					<li <?php echo(basename($_SERVER['SCRIPT_FILENAME'])=='settings.php'? 'class="active"' : '');
                        echo(basename($_SERVER['SCRIPT_FILENAME'])=='editeventtype.php'? 'class="active"' : '');
                        echo(basename($_SERVER['SCRIPT_FILENAME'])=='editSkills.php'? 'class="active"' : '');
                        echo(basename($_SERVER['SCRIPT_FILENAME'])=='locations.php'? 'class="active"' : ''); ?>><a href="settings.php"><i class="fa fa-gears"></i> <span>Settings</span></a></li>
					<?php
                    }  ?>
					<?php if (!isLoggedIn()): ?>
          <li <?php echo(basename($_SERVER['SCRIPT_FILENAME'])=='login.php'? 'class="active"' : '');?>><a href="login.php">Login</a></li>
          <?php endif; ?>
				</ul>
      </section>
		</aside>

			<?php /*
            <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            10:45 Service
            <small>Rotas</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Rotas</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">*/ ?>
