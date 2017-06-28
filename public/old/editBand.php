<?php namespace TechWilk\Rota; use DateInterval; use DateTime;
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

$action = $_GET['action'];
$bandID = $_GET['id'];

    $sql = "SELECT * FROM cr_bands WHERE bandID = '$bandID'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    
    while ($row =  mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $bandLeader = $row['bandLeader'];
    }



// If the form has been submitted, then we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bandLeader = $_POST['bandleader'];
    $rehearsaldate = $_POST['rehearsaldate'];
    
    if ($id == "") {
        $sql = "INSERT INTO cr_bands (bandLeader) VALUES ('$bandLeader')";
        mysqli_query(db(), $sql) or die(mysqli_error(db()));
        $bandID = mysqli_insert_id(db());
    } else {
        $sql = "UPDATE cr_bands SET bandLeader = '$bandLeader' WHERE bandID = '$bandID'";
        mysqli_query(db(), $sql) or die(mysqli_error(db()));
    }
    
    
        // Now we need to deal with the band changes
    
        // First of all, we need to delete all the people already exisiting on this week so we can repopulate with the correct data.
        $sql = "DELETE FROM cr_bandMembers WHERE bandID = '$bandID'";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));
        
    foreach ($rehearsaldate as $key => $rehearsaldatevalue) {
        addPeopleBand($bandID, $rehearsaldatevalue);
    }
        
    header("Location: viewBands.php");
}
$formatting = "true";

include('includes/header.php');
?>


<div class="elementBackground">
<h2>Edit a band</h2>

	<form action="editBand.php?id=<?php echo $bandID; ?>" method="post" id="createEvent">
		<fieldset>
			<label for="bandleader"></label>
			<input name="bandleader" id="bandleader" type="text" value="<?php echo $bandLeader; ?>" placeholder="Enter band leader" />
				
			
		</fieldset>
		<h2>Add people to the band:</h2>
		<fieldset>
					
			<?php
                $sqlPeople = "SELECT *,
				(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_skills`.`userID` ORDER BY `cr_users`.firstname) AS `name`, 
				(SELECT skillID FROM cr_bandMembers WHERE `cr_bandMembers`.`skillID` = `cr_skills`.`skillID` AND `cr_bandMembers`.`bandID` = '$bandID'
				LIMIT 1) AS `inBand` 
				FROM cr_skills WHERE groupID = 2 ORDER BY groupID, name";
                
                $resultPeople = mysqli_query(db(), $sqlPeople) or die(mysqli_error(db()));
                $i = 1;
                $position = 1;
            ?><div>
			<?php while ($viewPeople = mysqli_fetch_array($resultPeople, MYSQLI_ASSOC)) {
                $identifier = $viewPeople['groupID'];
                    
                if (isAdmin()) {
                    $usefulBits = " <a href='index.php?notifyIndividual=$viewPeople[userID]&eventID=$eventID&skillID=$viewPeople[skillID]'><img src='graphics/email.png' /></a> <a href='index.php?skillremove=true&eventID=$eventID&skillID=$viewPeople[skillID]'><img src='graphics/close.png' /></a> <br />";
                } else {
                    $usefulBits = "<br />";
                }
                                        
                if ($viewPeople['inBand'] != '') {
                    $checked =  'checked="checked"';
                } else {
                    $checked = "";
                }
                    
                if ($viewPeople['skill'] != "") {
                    $skill = " - <em>" . $viewPeople['skill'] . "</em>";
                } else {
                    $skill = "";
                }
                if ($position == 2) {
                    echo "<div class='checkboxitem right'><label class='styled' for='rehearsaldate[" .  $viewPeople['skillID'] . "]'>" .
                        $viewPeople['name'] .  $skill . "</em></label><input class='styled' " . $checked . "type='checkbox' id='rehearsaldate[" .
                        $viewPeople['skillID'] . "]'	name='rehearsaldate[]' value='" .
                        $viewPeople['skillID'] . "' /></div></div>";
                        
                    $position = 1;
                } else {
                    if ($i == "1") {
                        $class = "";
                        $i = "0";
                    } else {
                        $class = "";
                        $i = "1";
                    }
                        
                    echo "<div class='row" . $class . "'>
						<div class='checkboxitem'><label class='styled' for='rehearsaldate[" .  $viewPeople['skillID'] . "]'>" .
                        $viewPeople['name'] .  $skill . "</em></label><input class='styled' " . $checked . "type='checkbox' id='rehearsaldate[" .
                        $viewPeople['skillID'] . "]'	name='rehearsaldate[]' value='" .
                        $viewPeople['skillID'] . "' /></div>";
                        
                    $position = 2;
                }
            }
            if ($position == 2) {
                echo "</div>";
                $position = 1;
            }
            echo "</div>";
            
                    ?>
					<input type="submit" value="Edit band" />
				</fieldset>	
			
	</form>
</div>


<?php include('includes/footer.php'); ?>