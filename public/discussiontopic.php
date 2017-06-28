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
 
if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
    // Just continue the code
} else {
    header('Location: login.php');
    exit;
}

// Handle details from the header
$id = $_GET['id'];
$postid = $_GET['postid'];
$parentid = $_GET['parentid'];
$userID = $_SESSION['userid'];
$subscribe = $_GET['subscribe'];
$subscribestatus = $_GET['subscribestatus'];
$postremove = $_GET['postremove'];
$subscription = $_GET['subscription'];
$redirect = $_GET['redirect'];

// Method to remove  someone from the band
if ($postremove == "true") {
    removePost($postid);
    if ($redirect == "true") {
        header("Location: discussion.php?categoryid=" . $parentid);
    } else {
        header("Location: discussiontopic.php?id=" . $id . "&parentid=" . $parentid);
    }
}

if ($subscribe == "true") {
    subscribeto($userID, 0, $id);
    header("Location: discussiontopic.php?id=" . $id . "&parentid=" . $parentid);
} elseif ($subscribe == "false") {
    unsubscribefrom($subscription);
    header("Location: discussiontopic.php?id=" . $id . "&parentid=" . $parentid);
}

// If the form has been sent, we need to handle the data.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $discussiontopic = $_POST['discussiontopic'];
    $discussiontopic = mysqli_real_escape_string(db(), $discussiontopic);
    
    $discussion = $_POST['discussion'];
    $discussion = cleanUpTextarea($discussion);
    
      
    

    if ($id == "") {
        // If we are starting a new discussion
        $sql = ("INSERT INTO cr_discussion (CategoryParent, userID, topic, topicName, date) VALUES ('$parentid', '$userID', '$discussion', '$discussiontopic', NOW())");
        if (!mysqli_query(db(), $sql)) {
            die('Error: ' . mysqli_error(db()));
        }
        $type = "category";
        notifySubscribers($parentid, $type, $userID);
        header('Location: discussion.php?categoryid=' . $parentid);
        exit;
    } else {
        // Otherwise we reply in thread
        $sql = ("INSERT INTO cr_discussion (topicParent, CategoryParent, userID, topic, date) 
		VALUES ('$id', '$parentid', '$userID', '$discussion', NOW())");
        if (!mysqli_query(db(), $sql)) {
            die('Error: ' . mysqli_error(db()));
        }
        if ($subscribestatus == "false") {
            subscribeto($userID, 0, $id);
        }
        $type = "post";
        notifySubscribers($id, $type, $userID);
    }
}
    $formatting = "true";
    include('includes/header.php');
    
    if ($id == "") {
        // If there is no topic, we want to create one
    ?>
	
	<div class="elementBackground">
	<h2>New post</h2>
	<p>You are commenting as <strong><?php echo $_SESSION['name']; ?></strong></p>
	<form id="addTopic" method="post" action="discussiontopic.php?parentid=<?php echo $parentid; ?>">
				<fieldset>
					<label for="discussiontopic">Title:</label>
					<input id="discussiontopic" type="text" name="discussiontopic" placeholder="Enter discussion topic:" />					
					
					<label for="discussion">Your comment:</label>
					<textarea id="discussion" type="text" name="discussion"></textarea>
					
					
					
					<input type="submit" value="Add topic" />
				</fieldset>		
	</form>
	</div>
	<?php
    } else {
        // Otherwise we want to display the requested topic
    
    // First we get the heading post for the topic . . .
    $sql = "SELECT *, (SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_discussion`.`userID` ) AS `name`,
	DATE_FORMAT(date,'%W, %M %e @ %h:%i %p') AS dateFormatted	
	FROM cr_discussion WHERE id = '$id'";
        $result = mysqli_query(db(), $sql) or die();
    
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $topic = $row['topic']; ?>
	<div class="elementBackground highlight">
	<h2>Topic: <?php echo $row['topicName']; ?></h2>
	<p>You are commenting as <strong><?php echo $_SESSION['name']; ?></strong></p>
	</div>
	
	<div class="elementBackground">
	<h2><a name="addcategory"><?php echo $row['name']; ?></a> - <span class="postdate"><em><?php echo $row['dateFormatted']; ?></em></span> 
	<?php if (isAdmin()) {
                echo "<a href='discussiontopic.php?redirect=true&postremove=true&postid=" . $row['id'] . "&parentid=" . $parentid . "'><img src='graphics/close.png' /></a><br />";
            } ?>
	</h2>
	<p><?php echo stripslashes($topic); ?></p>
	</div>
	<?php
        }
    
    // Then we get all the posts that go in the topic
    $sql = "SELECT *, (SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_discussion`.`userID` ) AS `name`,
	DATE_FORMAT(date,'%W, %M %e @ %h:%i %p') AS dateFormatted	
	FROM cr_discussion WHERE topicParent = '$id' ORDER BY id";
    
        $result = mysqli_query(db(), $sql) or die();
    
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $topic = $row['topic']; ?>
	<div class="elementBackground">
	<h2><a name="addcategory"><?php echo $row['name']; ?></a> - <span class="postdate"><em><?php echo $row['dateFormatted']; ?></em></span>
	<?php if (isAdmin()) {
                echo "<a href='discussiontopic.php?postremove=true&postid=" . $row['id'] . "&parentid=" . $parentid . "&id=" . $id . "'><img src='graphics/close.png' /></a>";
            } ?></h2>
	<p><?php echo stripslashes($topic); ?></p>
	</div>
	
	<?php

    
    // At the end of the stream of posts, we want to have the possibility of replying
    $sql2 = "SELECT id FROM cr_subscriptions WHERE `cr_subscriptions`.userid = $userID AND `cr_subscriptions`.`topicid` = '$id'";
            $result2 = mysqli_query(db(), $sql2) or die(mysqli_error(db()));
        
            while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                // Check to see if we're subscribed or not. If yes, give the option to unsubscribe. If not, allow them to subscribe
            $isSubscribed = $row2['id'];
            }
        
            if ($isSubscribed != "") {
                $subscribe =  '<div class="item"><a href="discussiontopic.php?subscribe=false&subscription=' . $isSubscribed. '&id=' .
                    $id . '&parentid=' . $parentid . '">Unsubscribe from this post</a></div>';
                $subscribestatus = "true";
            } else {
                $subscribe = '<div class="item"><a href="discussiontopic.php?subscribe=true&id=' . $id . '&parentid=' . $parentid . '">
				Subscribe to this post</a></div>';
                $subscribestatus = "false";
            }
        } ?>
	
	<div class="elementBackground">
	<h2><a name="addcategory">Reply:</a></h2>
	<form id="addTopic" method="post" action="discussiontopic.php?id=<?php echo $id; ?>&subscribestatus=<?php echo $subscribestatus; ?>&parentid=<?php echo $parentid; ?>">
				<fieldset>			
					
					<label for="discussion">Your comment:</label>
					<textarea id="discussion" type="text" name="discussion"></textarea>
					
					
					
					<input type="submit" value="Add reply" />
				</fieldset>		
	</form>
	</div>
	

<?php
    }
?>
<div id="right">
		<?php echo $subscribe; ?>
		
</div>
<?php include('includes/footer.php'); ?>