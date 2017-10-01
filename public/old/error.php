<?php namespace TechWilk\Rota;

include 'includes/config.php';
include 'includes/functions.php';
/*
    This file is part of Church Rota.

    Copyright (C) 2011 Benjamin Schmitt

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

// Get the query string
$no = (isset($_GET['no']) ? $_GET['no'] : ' ');
$page = (isset($_GET['page']) ? $_GET['page'] : 'unknown');

include 'includes/header.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Error</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo siteSettings()->getSiteUrl() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Rotas</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">

<div class="elementBackground">
<h2>Error <?php echo $no; ?></h2>
<br>
The following error has occurred
<?php
echo " on page '".$page."':<br><br>";
    echo '<strong>';

    switch ($no) {
        case '100':
            echo 'No sufficient user rights to view that page!';
            break;
        default:
            echo 'No additional information were given.';
    }

    echo '</strong>';
?>

<a href="index.php" class="button">Back to main page</a>
</div>


<?php include 'includes/footer.php'; ?>
