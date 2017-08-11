<?php namespace TechWilk\Rota;

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

// Handle details from the header
$bandMembersID = $_GET['bandMembersID'];
$bandid = $_GET['bandid'];
$formAction = $_GET['formAction'];
$action = $_GET['action'];

// Method to remove  someone from the band
if ($bandMembersID != '') {
    removeBandMembers($bandMembersID);
} elseif ($bandid != '') {
    removeBand($bandid);
}

// If the form has been sent, we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($formAction == 'newBand') {
        $bandName = $_POST['bandname'];
        $sql = ("INSERT INTO cr_bands (bandLeader) VALUES ('$bandName')");
        if (!mysqli_query(db(), $sql)) {
            die('Error: '.mysqli_error(db()));
        }
    } else {
        $editbandID = $_GET['band'];
        $editskillID = $_POST['name'];

        $sql = ("INSERT INTO cr_bandMembers (bandID, skillID) VALUES ('$editbandID', '$editskillID')");
        if (!mysqli_query(db(), $sql)) {
            die('Error: '.mysqli_error(db()));
        }
    }
    // After we have inserted the data, we want to head back to the main page
     header('Location: viewBands.php');
    exit;
}

    include 'includes/header.php';

    if ($action == 'newBand') {
        ?>
<div class="elementBackground">
	<h2><a name="addBand">Add a new band:</a></h2>
	<form id="addBand" method="post" action="viewBands.php?formAction=newBand">
				<fieldset>
					<label for="bandname">Band name:</label>
					<input id="bandname" type="text" name="bandname" placeholder="Enter band name:" />
					<input type="submit" value="Add band" />
				</fieldset>		
	</form>
</div>
<div id="right">
			<div class="item"><a href="viewBands.php">View all bands</a></div>
		</div>
<?php
    } else {
        $sql = 'SELECT * FROM cr_bands ORDER BY bandLeader';
        $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $bandid = $row['bandID']; ?>
		<div class="elementBackground">
			<h2><a name="section<?php echo $row['bandID']; ?>"><?php echo $row['bandLeader']; ?></a> <?php if (isAdmin()) {
                echo "
			<a href='editBand.php?id=".$bandid."'><img src='graphics/tool.png' /></a>
			<a href='viewBands.php?skillremove=true&bandid=".$bandid."'><img src='graphics/close.png' /></a>";
            } ?></h2>
			
				<?php
                $bandID = $row['bandID'];

                // Selects band members from database and concatanates a username onto them.
                $sqlbandMembers = "SELECT * FROM cr_bandMembers WHERE bandID = $bandID";
            $resultbandMembers = mysqli_query(db(), $sqlbandMembers) or die(mysqli_error(db()));
            echo '<p>';
            while ($bandMember = mysqli_fetch_array($resultbandMembers, MYSQLI_ASSOC)) {
                if ($bandMember['bandID'] == $bandID) {
                    $sqlskills = "SELECT *,
						(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_skills`.`userID`) AS `name` 
						FROM cr_skills WHERE skillID = '$bandMember[skillID]' ORDER BY name";
                    $resultskills = mysqli_query(db(), $sqlskills) or die(mysqli_error(db()));

                    while ($rowskills = mysqli_fetch_array($resultskills, MYSQLI_ASSOC)) {
                        $bandMembersID = $bandMember['bandMembersID'];
                        $sqlusers = '';
                        echo '<strong>'.$rowskills['name'].'</strong> ';
                        echo '<em> '.$rowskills['skill'].'</em>'." <a href='viewBands.php?skillremove=true&bandMembersID=".$bandMembersID."'><img src='graphics/close.png' /></a><br />";
                    }
                }
            }
            echo '</p>';
            $sqladdMembers = "SELECT *,
				(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_skills`.`userID` ORDER BY `cr_users`.firstname) AS `name`
				FROM cr_skills WHERE groupID = 2 ORDER BY name";
            $resultaddMembers = mysqli_query(db(), $sqladdMembers) or die(mysqli_error(db())); ?>
			
			<form id="addMember<?php echo $row['bandID']; ?>" action="viewBands.php?formAction=newMember&band=<?php echo  $row['bandID']; ?>" method="post">
				<fieldset>
					<label for="name">Add members:</label>
					<select name="name" id="name">
						<?php while ($addMember = mysqli_fetch_array($resultaddMembers, MYSQLI_ASSOC)) {
                echo "<option value='".$addMember['skillID']."'>";
                echo $addMember['name'].' - '.$addMember['skill'];
                echo '</option>';
            } ?>
					</select>
					<input type="submit" value="Add member" />
				</fieldset>		
			</form>

		</div>		
		<div id="right">
			<div class="item"><a href="editBand.php">Add a new band</a></div>
		</div>
	<?php
        }
    }
?>
<?php include 'includes/footer.php'; ?>
