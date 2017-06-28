<?php
/*
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
*/

// Include files, including the database connection
include('includes/config.php');
include('includes/functions.php');

// Start the session. This checks whether someone is logged in and if not redirects them
session_start();

$sessionUserId = $_SESSION['userid'];

if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
    // continue code
} else {
    header("Location: login.php");
}


$queryStringUser = getQueryStringForKey('user');


// ensure user is allowed to see page

if (!isAdmin() && $queryStringUser != $sessionUserId) {
    header("Location: swaps.php?user=".$sessionUserId);
}


// fetch swaps

if (isAdmin()) {
    $swaps = SwapQuery::create()->filterByAccepted(false)->filterByDeclined(false)->find();
} else {
    $swaps = SwapQuery::create()->filterByAccepted(false)->filterByDeclined(false)->find(); // todo: where userId either in newUserRole / oldUserRole
}





    # ------ Presentation --------




$formatting = "light";
include('includes/header.php');
?>




	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>Pending Swaps
				<small>Rotas</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Rotas</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">

      <div class="row">
        <div class="col-sm-6">
          <?php foreach ($swaps as $swap):?>
          <?php $event = $swap->getEventPerson()->getEvent() ?>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h2 class="box-title">
                <a name="swap<?php echo $swap->getId() ?>" href="swap.php?swap=<?php echo $swap->getId() ?>"><?php echo $event->getName() != "" ? $event->getName() : "Requested swap" ?></a>
              </h2>
            </div>
            <div class="box-body">
              <p><?php echo $event->getEventType()->getName() ?>: <?php echo $event->getName() ?> (<?php echo $event->getDate('d M Y') ?>)</p>
              <p>
                <strong>
                  <s class="text-red">
                    <?php echo $swap->getOldUserRole()->getUser()->getFirstName() . ' ' . $swap->getOldUserRole()->getUser()->getLastName() ?> (<?php echo $swap->getOldUserRole()->getRole()->getName() ?>)
                  </s>
                  &#8594;
                  <span class="text-green">
                    <?php echo $swap->getNewUserRole()->getUser()->getFirstName() . ' ' . $swap->getNewUserRole()->getUser()->getLastName() ?> (<?php echo $swap->getNewUserRole()->getRole()->getName() ?>)
                  </span>
                </strong>
              </p>
            </div>
            <?php 
            $canAcceptSwap = canAcceptSwap($swap->getId());
            $canDeclineSwap = canDeclineSwap($swap->getId());
            ?>
            <?php if ($canAcceptSwap || $canDeclineSwap): ?>
            <div class="box-footer">
              <?php if ($canAcceptSwap): ?>
              <a class="btn btn-primary" href="swap.php?action=accept&swap=<?php echo $swap->getId() ?>">Accept</a>
              <?php endif; ?>
              <?php if ($canDeclineSwap): ?>
              <a class="btn" href="swap.php?action=decline&swap=<?php echo $swap->getId() ?>">Decline</a>
              <?php endif; ?>
            </div>
            <?php endif; ?>


          </div>
          <?php endforeach; ?>
        </div>
      </div>


<?php include('includes/footer.php'); ?>