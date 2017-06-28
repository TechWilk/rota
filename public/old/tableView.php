<?php namespace TechWilk\Rota; use DateInterval; use DateTime;
/*
    This file is part of Church Rota.

    Copyright (C) 2011 David Bunce, Benjamin Schmitt

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

$filter = getQueryStringForKey('filter');

// Start the session. This checks whether someone is logged in and if not redirects them
session_start();

if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
    // Just continue the code
} else {
    header('Location: login.php');
}


$sessionUserId = $_SESSION['userid'];






// ~~~~~~~~~~ PRESENTATION ~~~~~~~~~~


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Table view</title>
	<link rel="stylesheet" href="includes/style.css" type="text/css" />
	<style media="print">
		.no-print {
			display:none;
		}
	</style>
</head>

<?php

    $sqlSettings = "SELECT * FROM cr_settings";

    $resultSettings = mysqli_query(db(), $sqlSettings) or die(mysqli_error(db()));
    $rowSettings = mysqli_fetch_array($resultSettings, MYSQLI_ASSOC);
    $lang_locale = $rowSettings['lang_locale'];
    $time_format_short = $rowSettings['time_format_short'];
    $userTZ=$rowSettings['time_zone'];
    $google_group_calendar=$rowSettings['google_group_calendar'];


    if ($rowSettings['snapshot_show_two_month']=='1') {
        $whereTwoMonth = "Year(date) = Year(Now()) AND ((Month(date) = Month(Now())) OR ((Month(date) = Month(Now())+1) AND (Day(Now())>20)))";
        $whereTwoMonth .= " AND e.date >= DATE(NOW())";
    } else {
        if ($filter=='all') {
            $whereTwoMonth = "1=1";
        } else {
            $whereTwoMonth = "e.date >= DATE(NOW())";
        }
    }

    if ($rowSettings['group_sorting_name']=='1') {
        $group_sorting_name = "g.id, g.name";
    } else {
        $group_sorting_name = "g.id";
    }

    $sql = "SELECT count(*) AS colcount FROM cr_groups g";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $colCnt = $row['colcount']+2;

    if (isset($_GET['column_width'])) {
        $colWidth=$_GET['column_width'];
    } else {
        $colWidth=0; //full-size table, backward compatibility
    }





# --------- Presentation ---------


?>

<div class="filtersnapshot no-print">
	<a href="index.php">Return to homepage</a>
	<h1>Table view - <?php echo $owner; ?></h1>
		<h2>Filter events by:</h2>
		<p>
			<a class="eventTypeButton" href="tableView.php">All</a>
			<?php
            if ((isAdmin()) && ($rowSettings['snapshot_show_two_month']=='0')) {
                ?>
				<a class="eventTypeButton" href="tableView.php?filter=all">All (incl. past)</a>
		<?php
            }
        $filter_sql = "SELECT *
									 FROM cr_eventTypes et
									 WHERE id IN (SELECT e.type
										 						FROM cr_events e
																WHERE " . $whereTwoMonth . "
																AND e.removed = 0)
									 ORDER BY name";
        $result = mysqli_query(db(), $filter_sql) or die(mysqli_error(db()));

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            ?>
			<a class="eventTypeButton
				<?php
                    if ($filter == $row['id']) {
                        echo "activefilter";
                    } ?>" href="tableView.php?filter=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
			<?php
        }
        ?>
	</p>
</div>
<table class="snapshot" width='<?php echo(($colCnt)*$colWidth); ?>'>
<tr>
	<td ><strong>Event</strong></td>
	<?php
    $sql = "SELECT * FROM cr_groups g ORDER BY " . $group_sorting_name;
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo "<td><strong>";
        echo $row['name'];
        $categoryID[] = $row['id'];
        echo "</strong></td>";
    }
    //echo "<td class='no-print'><strong>Export</strong></td>";



    $sql = "SELECT
						*,
						(SELECT `name` FROM cr_eventTypes et WHERE et.id = e.type) AS eventType,
						(SELECT `name` FROM cr_eventSubTypes st WHERE st.id = e.subType) AS eventSubType,
						(SELECT l.name FROM cr_locations l WHERE l.id = e.location) AS eventLocation,
						(SELECT g.name FROM cr_eventGroups g WHERE g.id = e.eventGroup) AS eventGroup,
						name,
						comment,
						sermonTitle,
						bibleVerse,
						DATE_FORMAT(date,'%m/%d/%Y %H:%i:%S') AS sundayDate,
						DATE_FORMAT(rehearsalDate,'%m/%d/%Y %H:%i:%S') AS rehearsalDateFormatted,
						DATE_FORMAT(DATE_ADD(date, INTERVAL 90 MINUTE),'%m/%d/%Y %H:%i:%S') AS sundayEndDate
					FROM
						cr_events e
					WHERE
						e.removed = 0";
                    

    if ($filter == "") {
        $sql .= "
							AND " . $whereTwoMonth . "
						ORDER BY
							e.date";
    } elseif ($filter == "all") {
        $sql .= "
						ORDER BY
							e.date";
    } elseif ($filter != "") {
        $sql .= "
							AND e.type = '$filter'
							AND " . $whereTwoMonth . "
						ORDER BY
							e.date";
    }

    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $eventID = $row['id'];
        $comment = $row['comment'];
        $preacher="";
        $leader="";
        $band="";
        echo "<tr>";
        echo "<td >";
        setlocale(LC_TIME, $lang_locale); //de_DE
        echo '<a href="event.php?id='.$row['id'].'">';
        echo strftime($time_format_short, strtotime($row['sundayDate'])); // %a, <strong>%e. %b</strong>, KW%V
        echo '</a>';

        //$row['sundayDate']
        if (!empty($row['eventType'])) {
            echo "<br /><em>&nbsp;&nbsp;&nbsp;" . $row['eventType'] . "</em>";
        }
        if (!empty($row['eventSubType'])) {
            echo " - <em>" . $row['eventSubType'] . "</em>";
        }
        if (!empty($row['eventLocation'])) {
            echo "<br /><em>&nbsp;&nbsp;&nbsp;" . $row['eventLocation'] . "</em>";
        }
        if (!empty($row['name'])) {
            echo "<br /><em>&nbsp;&nbsp;&nbsp;" . $row['name'] . "</em>";
        }
        if (!empty($row['eventGroup'])) {
            echo "<br /><strong>&nbsp;&nbsp;&nbsp;" . $row['eventGroup'] . "</strong>";
        }
        if (!empty($row['sermonTitle'])) {
            echo ": " . $row['sermonTitle'];
        }
        if (!empty($row['bibleVerse'])) {
            echo " <em>(" . $row['bibleVerse'] . ")</em>";
        }
        if (!empty($row['comment'])) {
            echo "<br /><em>&nbsp;&nbsp;&nbsp;(" . $row['comment'] . ")</em>";
        }
        echo "</td>";


        // GROUPS columns
                $sqlPeople = "SELECT *,
											CONCAT(LEFT(u.firstname,1),'. ',u.lastname) AS name,
											u.id AS userId,
											g.name category,
											GROUP_CONCAT(r.name SEPARATOR ', ') AS role
											FROM cr_userRoles ur
											INNER JOIN cr_roles r ON r.id = ur.roleId
											INNER JOIN cr_groups g ON g.id = r.groupId
											INNER JOIN cr_users u ON ur.userId = u.id
											INNER JOIN cr_eventPeople ep ON ep.userRoleId = ur.id
											WHERE ep.eventId = '$eventID'
											GROUP BY u.id
											ORDER BY r.name";

        for ($i=0;$i<count($categoryID);$i++) {
            $peopleInEvent = false;
            echo "<td>";
            $resultPeople = mysqli_query(db(), $sqlPeople) or die(mysqli_error(db()));
            $previousName = "";
            while ($viewPeople = mysqli_fetch_array($resultPeople, MYSQLI_ASSOC)) {
                $groupID = $viewPeople['groupId'];
                if ($groupID==$categoryID[$i]) {
                    $name = $viewPeople['name'];
                            //writing name/s into table cell
                            if ($previousName == "") {
                                // new name
                                echo ($viewPeople['userId'] == $sessionUserId) ? '<strong class="me">' : '';
                                echo $name . " <em>(" . $viewPeople['role'];
                            } elseif ($previousName != $name) {
                                echo ")</em>";
                                echo ($viewPeople['userId'] != $sessionUserId) ? '</strong>' : '';
                                echo "<br />"; // line break from previous name
                                // new name
                                echo ($viewPeople['userId'] == $sessionUserId) ? '<strong class="me">' : '';
                                echo $name . " <em>(" . $viewPeople['role'];
                            } else {
                                echo ", " . $viewPeople['role'];
                            }

                    $peopleInEvent = true;
                    $previousName = $viewPeople['name'];

                            //no break or continue, because there could be other viewPeople with same categoryID
                }
            } // end of while
                    if ($peopleInEvent) {
                        echo ")</em>";
                        echo ($viewPeople['userId'] == $sessionUserId) ? '</strong>' : '';
                    }
            echo "</td>";
        }
                /*
                // EXPORT column
                echo "<td class='no-print'>";

                //generate google calendar urls
                putenv("TZ=".$userTZ);
                $eventDate = $row['sundayDate'];
                $eventDateGMT = gmdate("Ymd\THis\Z",strtotime($eventDate." ".date("T",strtotime($eventDate))));
                //echo $eventDateGMT."<br>";
                $eventDate = $row['sundayEndDate'];
                $eventDateEndGMT = gmdate("Ymd\THis\Z",strtotime($eventDate." ".date("T",strtotime($eventDate))));
                //echo $eventDateEndGMT;

                if (isAdmin()) {
                    if ($comment<>"")
                        $comment = "; " . $comment;
                    $comment = str_replace("\r\n","; ",$comment);
                    echo "<a href=\"http://www.google.com/calendar/event?action=TEMPLATE&text=".urlencode(utf8_wrapper(getEventDetails($eventID, " / ",1)." (".$row['eventType'].")"))."&dates=".$eventDateGMT."/".$eventDateEndGMT."&details=".urlencode(utf8_wrapper(ltrim(getEventDetails($eventID, "; ",0,false) . $comment,"; ")))."&location=".urlencode(utf8_wrapper($row['eventLocation']))."&trp=false&sprop=&sprop=name:&src=".$google_group_calendar."&ctz=".$userTZ."\" target=\"_blank\">";
                    echo "<img src=\"//www.google.com/calendar/images/ext/gc_button1.gif\" border=0></a><BR>";
                    //echo "iCal";
                }else{
                    echo "<a href=\"http://www.google.com/calendar/event?action=TEMPLATE&text=".urlencode(utf8_wrapper($row['eventType']))."&dates=".$eventDateGMT."/".$eventDateEndGMT."&details=&location=".urlencode(utf8_wrapper($row['eventLocation']))."&trp=false&sprop=&sprop=name:&ctz=".$userTZ."\" target=\"_blank\">";
                    echo "<img src=\"//www.google.com/calendar/images/ext/gc_button1.gif\" border=0></a>";
                }

                echo "</td>";
                */

        echo "</tr>\r\n";
    }
?>
</table>
</body>
</html>
