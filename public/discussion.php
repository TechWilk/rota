<?php
/*
    This file is part of Church Rota.

    Copyright (C) 2011 David Bunce

    Church Rota is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
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

// Handle details from the header
$areaid = $_GET['categoryid'];
$subscribe = $_GET['subscribe'];
$categoryremove = $_GET['categoryremove'];
$discussionremove = $_GET['discussionremove'];
$subscription = $_GET['subscription'];
$userID = $_SESSION['userid'];
$action = $_GET['action'];

if ($categoryremove == "true") {
    removeCategory($areaid);
}

if ($discussionremove == "true") {
    removeDiscussion($areaid);
}

if ($subscribe == "true") {
    subscribeto($userID, $areaid, 0);
    header("Location: discussion.php?categoryid=" . $areaid);
} elseif ($subscribe == "false") {
    unsubscribefrom($subscription);
    header("Location: discussion.php?categoryid=" . $areaid);
}

// If the form has been sent, we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryname = $_POST['categoryname'];
    $categoryparent = $_POST['categoryparent'];
    $categorydescription = $_POST['categorydescription'];
        
    $sql = ("INSERT INTO cr_discussionCategories(name, description, parent) VALUES ('$categoryname', '$categorydescription', '$categoryparent')");
    if (!mysqli_query(db(), $sql)) {
        die('Error: ' . mysqli_error(db()));
    }
    
    // After we have inserted the data, we want to head back to the main page
     header('Location: discussion.php');
    exit;
}
    include('includes/header.php');
    // This is the categories selection of the thing
    $sql = "SELECT *,
	(SELECT id FROM cr_discussion WHERE cr_discussionCategories.id = cr_discussion.CategoryParent ORDER BY id DESC LIMIT 1) AS `postid`,
	(SELECT topicName FROM cr_discussion WHERE cr_discussionCategories.id = cr_discussion.CategoryParent ORDER BY id DESC LIMIT 1) AS `topicName`,
	(SELECT topicParent FROM cr_discussion WHERE cr_discussionCategories.id = cr_discussion.CategoryParent ORDER BY id DESC LIMIT 1) AS `topicParent`,
	(SELECT userID FROM cr_discussion WHERE cr_discussionCategories.id = cr_discussion.CategoryParent ORDER BY id DESC LIMIT 1) AS `userID`,
	(SELECT firstName FROM cr_users WHERE userID = cr_users.id ORDER BY id DESC LIMIT 1) AS `firstName`
	FROM cr_discussionCategories WHERE parent = '$areaid'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $parent = $row['parent'];
        $categoryID = $row['id'];
        $categoryName = $row['name'];

    
    // The following function lists the parents here to save a SQL query later
    $listParents = $listParents . "<option value='" . $categoryID . "'>" . $categoryName . "</option>"; ?>
		<div class="elementBackground highlight">
			<h2><a href="discussion.php?categoryid=<?php echo $categoryID; ?>"><?php echo $row['name']; ?></a> <?php 
            if (isAdmin()) {
                echo "<a href='discussion.php?categoryremove=true&categoryid=$categoryID'><img src='graphics/close.png' /></a>";
            } ?></h2>
			<p><?php echo $row['description']; ?></p>
            <?php if ($row['firstName'] != "") {
                // If there is no topic name, get the topic name of the parent topic
                if ($row['topicName'] == "") {
                    $topichandler = $row['topicParent'];
                    $sqlTopicName = "SELECT * FROM cr_discussion WHERE id = '$topichandler'";
                    $resultTopicName = mysqli_query(db(), $sqlTopicName) or die(mysqli_error(db()));
    
                    while ($rowTopicName = mysqli_fetch_array($resultTopicName, MYSQLI_ASSOC)) {
                        $topicName = $rowTopicName['topicName'];
                    }
                } else {
                    $topicName = $row['topicName'];
                } ?>
			<p><strong>Latest post:</strong> <?php echo $topicName . " <strong>by</strong> " . $row['firstName']; ?></p>
            <?php
            } ?>
		
		</div>		
	<?php
    }
    
    if ($areaid == "") {
        $sql = "SELECT *, 
		(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_discussion`.`userID` ) AS `name`,
		DATE_FORMAT(date,'%W, %M %e @ %h:%i %p') AS dateFormatted 
		FROM cr_discussion WHERE CategoryParent = 0 AND topicParent = 0";
    } else {
        $sql = "SELECT *, 
		(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_discussion`.`userID` ) AS `name`, 
		(SELECT id FROM cr_discussion WHERE cr_discussion.CategoryParent = '$areaid' ORDER BY cr_discussion.id DESC LIMIT 0,1) AS `postid`,
		(SELECT DATE_FORMAT(date,'%W, %M %e @ %h:%i %p') FROM cr_discussion WHERE cr_discussion.CategoryParent = '$areaid' ORDER BY cr_discussion.id DESC 
		LIMIT 1) AS `topicDate`,
		(SELECT userID FROM cr_discussion WHERE cr_discussion.CategoryParent = '$areaid' ORDER BY cr_discussion.id DESC LIMIT 1) AS `userID`,
		(SELECT firstName FROM cr_users WHERE userID = cr_users.id ORDER BY cr_discussion.id DESC LIMIT 1) AS `firstName`,
		DATE_FORMAT(date,'%W, %M %e @ %h:%i %p') AS dateFormatted
		FROM cr_discussion WHERE CategoryParent = '$areaid' AND topicParent = 0";
    }
    
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $categoryID = $row['CategoryParent'];
        $categoryName = $row['name'];
        $postparent = $row['id']; ?>
		<div class="elementBackground">
			<h2><a href="discussiontopic.php?id=<?php echo $row['id']; ?>&parentid=<?php echo $categoryID; ?>"><?php echo $row['topicName']; ?></a>
			<?php if (isAdmin()) {
            echo "<a href='discussion.php?discussionremove=true&categoryid=" . $row['id'] . "'><img src='graphics/close.png' /></a>";
        } ?></h2>
			<p><strong>Posted:</strong> <?php echo $row['dateFormatted']; ?> <strong>by</strong> <?php echo $row['name']; ?></p>
			 <?php 
             $latestpostsql = "SELECT *, 
		(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_discussion`.`userID` ) AS `name`, 
		(SELECT id FROM cr_discussion WHERE cr_discussion.CategoryParent = '$areaid' ORDER BY cr_discussion.id DESC LIMIT 0,1) AS `postid`,
		(SELECT DATE_FORMAT(date,'%W, %M %e @ %h:%i %p') FROM cr_discussion WHERE cr_discussion.CategoryParent = '$areaid' ORDER BY cr_discussion.id DESC 
		LIMIT 1) AS `topicDate`,
		(SELECT userID FROM cr_discussion WHERE cr_discussion.CategoryParent = '$areaid' ORDER BY cr_discussion.id DESC LIMIT 1) AS `userID`,
		(SELECT firstName FROM cr_users WHERE userID = cr_users.id ORDER BY cr_discussion.id DESC LIMIT 1) AS `firstName`,
		DATE_FORMAT(date,'%W, %M %e @ %h:%i %p') AS dateFormatted
		FROM cr_discussion WHERE CategoryParent = 9 AND topicParent = '$postparent'";
        $latestpostresult = mysqli_query(db(), $latestpostsql) or die(mysqli_error(db()));
             
        while ($latestpostrow = mysqli_fetch_array($latestpostresult, MYSQLI_ASSOC)) {
            ?>
			<p><strong>Latest post:</strong> <?php echo $latestpostrow['firstName'] . " <strong>on</strong> " . $latestpostrow['topicDate']; ?></p>
            <?php
        } ?>	
		</div>
		<?php
    }
    ?>
	<?php if ($areaid != "") {
        $sql = "SELECT id FROM cr_subscriptions WHERE `cr_subscriptions`.userid = $userID AND `cr_subscriptions`.`categoryid` = '$areaid'";
        $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
        
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            // Check to see if we're subscribed or not. If yes, give the option to unsubscribe. If not, allow them to subscribe
            $isSubscribed = $row['id'];
        }
    
        if ($isSubscribed != "") {
            $subscribe =  '<div class="item"><a href="discussion.php?subscribe=false&subscription=' . $isSubscribed. '&categoryid=' .
                    $areaid . '">Unsubscribe from this area</a></div>';
        } else {
            $subscribe = '<div class="item"><a href="discussion.php?subscribe=true&categoryid=' . $areaid . '">Subscribe to this area</a></div>';
        }
    } ?>
	<?php
if (isAdmin() && $action == 'newcategory') {
        ?>
<div class="elementBackground">
	<h2><a name="addcategory">Add a new category:</a></h2>
	<form id="addCategory" method="post" action="discussion.php">
				<fieldset>
					<label for="categoryname">Category name:</label>
					<input id="categoryname" type="text" name="categoryname" placeholder="Enter category name:" />
					
					<label for="categoryparent">Category parent:</label>
					<select name="categoryparent" id="categoryparent">
						<option value="0"></option>
						<?php echo $listParents; ?>
					</select>
					
					
					<label for="categorydescription">Resource description:</label>
					<textarea id="categorydescription" type="text" name="categorydescription"></textarea>
					
					
					
					<input type="submit" value="Add description" />
				</fieldset>		
	</form>
</div>
<?php
    } // End the admin loop
 ?>
<div id="right">
		<?php echo $subscribe;
        
        if (isAdmin()) {
            ?><div class="item"><a href="discussion.php?action=newcategory">Add a new category</a></div><?php
        }
        if ($areaid == "") {
        } else {
            ?>
      		<div class="item"><a href="discussiontopic.php?parentid=<?php echo $areaid; ?>">Add a new post</a></div>
        <?php
        } ?>
	</div>
<?php include('includes/footer.php'); ?>
