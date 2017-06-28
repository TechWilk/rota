<?php namespace TechWilk\Rota;

use DateInterval;
use DateTime;

use Mailgun\Mailgun;

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


function sendMail($to, $subject, $message, $from, $bcc = "")
{
    $dbMailId = mailToDb($to, $subject, $message, $from, $bcc);

    $subject = "[rota] " . $subject;

    $message .= "\n\n";
    $message .= siteSettings()->getOwner() . "\n";
    $message .= "Mail generated with ChurchRota V." . siteSettings()->getVersion() . "\n";
    $message .= siteSettings()->getSiteUrl() . "\n";

    $mailSentOk = false;

    switch (siteConfig()['email']['method']) {
        case 'mailgun':
            try {
                $mailSentOk = sendViaMailgun($to, $subject, $message, $from, $bcc);
            } catch (\Exception $e) {
                logFailedMailWithId($dbMailId, $e->getMessage());
                insertStatistics('system', __FILE__, $e->getMessage(), 'mailgun-error', $to);
                return false;
            }
            break;
        
        case 'sendmail':
            $mailSentOk = sendViaSendmail($to, $subject, $message, $from, $bcc);
            break;
        
        default:
            var_dump($dbMailId);
            exit;
            break;
    }

    if ($mailSentOk != true) {
        logFailedMailWithId($dbMailId, "Unknown send error");
    }
    return $mailSentOk;
}





function sendViaSendmail($to, $subject, $message, $from, $bcc = "")
{
    //mail debugging: send all mails to admin (overwrite TO-field)
    //and move all BCCs from header to the end of the message
    $mail_dbg = false;

    //--------------------------------------------------------------------------------
    //line seperator for mails
    //rfc sais \r\n, but this makes trouble in outlook. several spaces plus \n works fine in outlook and thunderbird.
    //spaces to suppress automatic removal of "unnecessary linefeeds" in outlook 2003
    $crlf="      \n";
    $message = str_replace("\r\n", $crlf, $message);  //replace crlf's

    //--------------------------------------------------------------------------------
    $headers = 'From: ' . $from . $crlf .
    'Reply-To: ' .$from . $crlf .
    'Mime-Version: 1.0' . $crlf .
    'Content-Type: text/plain; charset=ISO-8859-1' . $crlf;
    'Content-Transfer-Encoding: quoted-printable' . $crlf;
    $headerSimple = $headers;

    //--------------------------------------------------------------------------------
    //replace all possible seperating semikolons with commas (for later explode)
    $bcc = str_replace(";", ",", $bcc);
    $to = str_replace(";", ",", $to);

    if ($mail_dbg) {
        $subject = " - Mail Debug: " . $subject;   //debug output
        $message .= $crlf . 'To: ' . $to . $crlf;   //debug move to to end of message
    }

    //--------------------------------------------------------------------------------
    //break bcc string into single BCC header lines, ignoring all invalid email addresses
    $teile = explode(",", $bcc);
    $i=0;
    $err=0;
    $bcc_err="<br>";
    foreach ($teile as $adr) {
        if (preg_replace("/([a-zA-Z0-9._%+-]+)(@)([a-zA-Z0-9.-]+)(\.)([a-zA-Z]+)/i", "# # #", trim($adr))=="# # #") {
            if ($mail_dbg) {
                $message .= 'Bcc: ' . trim($adr) . $crlf;
            } else {
                $headers .= 'Bcc: ' . trim($adr) . $crlf;
            }
            $i=$i+1;
        } else {
            $bcc_err .= $adr . $crlf;
            $err .= + 1;
        }
    }
    //echo str_replace($crlf,"<br>\r\n",$headers)."<br>\r\n";  //debug output
    //--------------------------------------------------------------------------------
    //general mail footer
    $sqlSettings = "SELECT * FROM cr_settings";
    $resultSettings = mysqli_query(db(), $sqlSettings) or die(mysqli_error(db()));
    $rowSettings = mysqli_fetch_array($resultSettings, MYSQLI_ASSOC);
    $cr_version = $rowSettings['version'];
    $cr_owner = $rowSettings['owner'];

    // $message .= $crlf . $crlf;
    // $message .= "-- \r\n"; //needs exactly this syntax, only one space before linebreak
    // $message .= $cr_owner . $crlf;
    // $message .= "Mail generated with ChurchRota V." . $cr_version . $crlf;
    // $message .= "http://sourceforge.net/projects/churchrota" . $crlf;

    //--------------------------------------------------------------------------------
    //send mail
    //--------------------------------------------------------------------------------
    
    $mailOk = false;
    
    if ($mail_dbg) {
        $mailOk = mail($from, $subject, $message, $headers);
    } else {
        $mailOk = mail($to, $subject, $message, $headers);
        
        if ($mailOk) {
            //mail($from, "[ChurchRota] Mail status - OK", "address ok: " . $i, $headerSimple);
        } else {
            mail($from, "[Rota] Mail status - ERROR", "to: " . $to . $crlf . "address ok: " . $i . $crlf . "address errors: " . $err . $crlf . $bcc_err, $headerSimple);
            return false;
            logFailedMailWithId($dbMailId, "address ok: " . $i . $crlf . "address errors: " . $err . $crlf . $bcc_err, $headerSimple);
        }
    }
    return $mailOk;
}




// returns either true or false
function sendViaMailgun($to, $subject, $message, $from, $bcc = "")
{
    # First, instantiate the SDK with your API credentials and define your domain.
    $mg = new Mailgun(siteConfig()['email']['mailgun']['apiKey']);
    $domain = siteConfig()['email']['mailgun']['domain'];

    $status = $mg->sendMessage($domain, array('from'    => $from,
                                                                    'to'      => $to,
                                                                    'subject' => $subject,
                                                                    'text'    => $message));

    if ($status->http_response_code = 200) {
        return true;
    }
    return false;
}





function notifySubscribers($id, $type, $userid)
{
    if ($type == "category") {
        $sql = "SELECT *,
		(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_subscriptions`.`userID`) AS `name`,
		(SELECT email FROM cr_users WHERE `cr_users`.id = `cr_subscriptions`.`userID`) AS `email`,
		(SELECT name FROM cr_discussionCategories WHERE `cr_discussionCategories`.id = `cr_subscriptions`.`categoryid`) AS `categoryname`,
		(SELECT topicName FROM cr_discussion WHERE `cr_discussion`.id = `cr_subscriptions`.`topicid` GROUP BY topicname) AS topicname,
		(SELECT `adminemailaddress` FROM cr_settings) AS `siteadmin`
		FROM cr_subscriptions WHERE categoryid = '$id' AND userid != '$userid'";
        $message = "There has been a new post in the following category: ";
    } elseif ($type == "post") {
        $sql = "SELECT *, (SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_subscriptions`.`userID`)
		AS `name`, (SELECT email FROM cr_users WHERE `cr_users`.id = `cr_subscriptions`.`userID`) AS `email`,
		(SELECT `adminemailaddress` FROM cr_settings) AS `siteadmin`,
		(SELECT name FROM cr_discussionCategories WHERE `cr_discussionCategories`.id = `cr_subscriptions`.`categoryid`)
		AS `categoryname`, (SELECT topicName FROM cr_discussion WHERE `cr_discussion`.id = `cr_subscriptions`.`topicid` GROUP BY topicname)
		AS topicname FROM cr_subscriptions WHERE topicid = '$id' AND userid != '$userid'";
        $message = "There has been a new post in the following discussion: ";
    }
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $postname = $row['name'];
        if ($type == "category") {
            $objectname = $row['categoryname'];
        } elseif ($type == "post") {
            $objectname = $row['topicname'];
        }
        $categoryname = $row['categoryname'];
        $to = $row['email'];
        $subject = "New post: " . $objectname;

        $finalmessage = "Dear " . $postname . "\n \n" . $message . $objectname . "\n \n" .
        "To see the post, please login using your username and password";

        sendMail($to, $subject, $finalmessage, $row['siteadmin']);
    }
}



function mailNewUser($userId, $password = "*******")
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT firstName, lastName, email, username FROM cr_users WHERE id = $userId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $user = mysqli_fetch_object($result);
    
    if (strlen($user->email) == 0) {
        return;
    }

    $message = siteSettings()->getNewUserMessage();

    $name = $user->firstName . " " . $user->lastName;
    $message = str_replace("[name]", $name, $message);
    $message = str_replace("[username]", $user->username, $message);
    $message = str_replace("[password]", $password, $message);
    $message = str_replace("[siteurl]", siteSettings()->getSiteUrl(), $message);

    $subject = "Account created for " . $name;

    $to = $name . ' <' . $user->email . '>';
    $from = siteSettings()->getOwner() . ' <' . siteSettings()->getAdminEmailAddress() . '>';

    sendMail($to, $subject, $message, $from);
    sendMail($from, "ADMIN COPY: " . $subject, $message, $from);
}




function parseEmailTemplate($message, $values)
{
    $values['url'] = siteSettings()->getSiteUrl();
    $values['version'] = siteSettings()->getVersion();
    $values['sitename'] = siteSettings()->getOwner();
    $values['siteemail'] = siteSettings()->getAdminEmailAddress();

    foreach ($values as $template_field => $value) {
        $message = str_ireplace('['.$template_field.']', trim($value), $message);
    }
    $message = trim($message);

    return $message;
}




function emailTemplate($message, $name, $date, $location, $rehearsal, $rotaoutput, $username, $siteurl, $type="", $rotadetails="", $eventdetails="", $comment="")
{
    $skillfinal = '';
    $message = trim(str_replace("[name]", $name, $message));
    $message = str_replace("[date]", $date, $message);
    $message = str_replace("[location]", $location, $message);
    $message = str_replace("[rehearsal]", $rehearsal, $message);
    if (is_array($rotaoutput)):
        foreach ($rotaoutput as $key => $skill):
            $skillfinal = $skillfinal . $skill . ' ';
    endforeach; else:
        $skillfinal = $rotaoutput;
    endif;
    $message = str_replace("[rotaoutput]", $skillfinal, $message);
    $message = str_replace("[siteurl]", $siteurl, $message);
    $message = str_replace("[username]", $username, $message);
    $message = str_replace("[type]", $type, $message);
    $message = str_replace("[rotadetails]", $rotadetails, $message);
    $message = str_replace("[eventdetails]", $eventdetails, $message);
    $message = str_replace("[comment]", $comment, $message);
    // echo '<p>' . $message . '</p>';
    return $message;
}



function notifyIndividual($userID, $eventID, $skillID)
{
    notifyEveryoneForEvent($eventID, $skillID, $userID);
    $eventID=0;  //disables following code through empty sql result

    $sql = "SELECT *,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = `cr_skills`.`userID` ORDER BY `cr_users`.firstname) AS `name`,
	(SELECT email FROM cr_users WHERE `cr_users`.id = `cr_skills`.`userID`) AS `email`,
	(SELECT id FROM cr_users WHERE `cr_users`.id = `cr_skills`.`userID`) AS `userid`,
	(SELECT username FROM cr_users WHERE `cr_users`.id = `cr_skills`.`userID`) AS `username`,
	(SELECT `notificationemail` FROM cr_settings ) AS `notificationmessage`,
	(SELECT `adminemailaddress` FROM cr_settings) AS `siteadmin`,
	(SELECT `norehearsalemail` FROM cr_settings) AS `norehearsalemail`,
	(SELECT `yesrehearsal` FROM cr_settings) AS `yesrehearsal`,
	(SELECT `siteurl` FROM cr_settings) AS `siteurl`,
	(SELECT `type` FROM cr_events WHERE id = '$eventID') AS `eventType`,
	(SELECT `location` FROM cr_events WHERE id = '$eventID') AS `eventLocation`,
	(SELECT `description` FROM cr_eventTypes WHERE cr_eventTypes.id = eventType) AS eventTypeFormatted,
	(SELECT `rehearsal` FROM cr_eventTypes WHERE cr_eventTypes.id = eventType) AS eventRehearsal,
	(SELECT `rehearsal` FROM cr_events WHERE id = '$eventID') AS `eventRehearsalChange`,
	(SELECT `description` FROM cr_locations WHERE cr_locations.id = eventLocation) AS eventLocationFormatted,
	(SELECT `description` FROM cr_groups WHERE `cr_skills`.`groupID` = `cr_groups`.`groupID`) AS `category`,
	(SELECT `rehearsal` FROM cr_groups WHERE `cr_skills`.`groupID` = `cr_groups`.`groupID`) AS `rehearsal`, GROUP_CONCAT(skill) AS joinedskill
	FROM cr_skills WHERE skillID IN (SELECT skillID FROM cr_eventPeople WHERE eventID = '$eventID')
	AND skillID = '$skillID' GROUP BY userID, groupID ORDER BY groupID";
    $userresult = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    while ($row = mysqli_fetch_array($userresult, MYSQLI_ASSOC)) {
        $eventsql = "SELECT *, DATE_FORMAT(date,'%W, %M %e') AS sundayDate, DATE_FORMAT(rehearsalDate,'%W, %M %e @ %h:%i %p') AS rehearsalDateFormatted FROM cr_events WHERE id = $eventID ORDER BY date";
        $eventresult = mysqli_query(db(), $eventsql) or die(mysqli_error(db()));

        $location = $row['eventLocationFormatted'];

        while ($eventrow = mysqli_fetch_array($eventresult, MYSQLI_ASSOC)) {
            $date = $eventrow['sundayDate'];

            $rehearsaldate = $eventrow['rehearsalDateFormatted'];
        }

        $identifier = $row['groupID'];
        if ($row['rehearsal'] == "1") {
            if (($row['eventRehearsal'] == "0")  or ($row['eventRehearsalChange'] == "1")) {
                $rehearsal = $row['norehearsalemail'];
            } else {
                $rehearsal = $row['yesrehearsal'] . " on " . $rehearsaldate . " at " . $location;
            }
        }

        $skill = $row['category'];
        if ($row['joinedskill'] != "") {
            $skill = $skill . " - " . $row['joinedskill'];
        } else {
            // If there is no skill, then we don't need to mention this fact.
        }
        $temp_user_id = $row['userid'];

        $sql = "UPDATE cr_eventPeople SET notified = '1' WHERE skillID = '$skillID' AND eventID = '$eventID'";
        mysqli_query(db(), $sql) or die(mysqli_error(db()));


        $message = $row['notificationmessage'];
        $siteurl = $row['siteurl'];
        $username = $row['username'];
        $name = $row['name'];
        $location = $row['eventLocationFormatted'];
        $rotaoutput = $skill;
        $to = $row['email'];
        $subject = "Rota reminder: " . $date;


        $message = emailTemplate($message, $name, $date, $location, $rehearsal, $rotaoutput, $username, $siteurl);

        sendMail($to, $subject, $message, $row['siteadmin']);
    }
}



function notifyEveryoneForEvent($eventId)
{
    setlocale(LC_TIME, siteSettings()->getLangLocale());

    $sql = "SELECT
						DISTINCT u.id AS userId,
						CONCAT(u.firstname, ' ', u.lastname) AS name,
						u.firstname AS firstname,
						u.email AS email,
						u.username AS username,
						r.name AS role,
						t.name AS eventType,
						l.name AS location
					FROM
						cr_events e
						INNER JOIN cr_eventTypes t ON t.id = e.type
						INNER JOIN cr_locations l ON l.id = e.location
						INNER JOIN cr_eventPeople ep ON ep.eventId = e.id
						INNER JOIN cr_userRoles ur ON ur.id = ep.userRoleId
						INNER JOIN cr_users u ON u.id = ur.userId
						INNER JOIN cr_roles r ON r.id = ur.roleId
						INNER JOIN cr_groups g ON g.id = r.groupId
					WHERE
						e.id = $eventId
						AND ep.notified = 0
						AND e.removed = 0
					GROUP BY
						u.id,
						r.id
					ORDER BY
						g.id";

    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $countarray = array();

    while ($ob = mysqli_fetch_object($result)) {
        $role = '';
        $thisId = $ob->userId;

        if (!in_array($thisId, $countarray)) {
            $eventsql = "SELECT
										*,
										eg.name AS eventGroup,
										DATE_FORMAT(date,'%m/%d/%Y %H:%i:%S') AS sundayDate,
										DATE_FORMAT(rehearsalDate,'%m/%d/%Y %H:%i:%S') AS rehearsalDateFormatted
									FROM
										cr_events e
										LEFT JOIN cr_eventGroups eg ON eg.id = e.eventGroup
									WHERE
										e.id = $eventId ORDER BY date";
            
            $eventresult = mysqli_query(db(), $eventsql) or die(mysqli_error(db()));
            $location = $ob->location;

            while ($eventrow = mysqli_fetch_array($eventresult, MYSQLI_ASSOC)) {
                //$date = $eventrow['sundayDate'];
                $date = strftime(siteSettings()->getTimeFormatNormal(), strtotime($eventrow['sundayDate']));

                //$rehearsaldate = $eventrow['rehearsalDateFormatted'];
                $rehearsaldate = strftime(siteSettings()->getTimeFormatNormal(), strtotime($eventrow['rehearsalDateFormatted']));

                $type = $ob->eventType;
                $comment = $eventrow['comment'];
                if ($comment == '') {
                    $comment = '-';
                }
            

                $eventdetails = $eventrow['eventGroup'] . ": " . $eventrow['sermonTitle'];
                if (!empty($eventrow['bibleVerse'])) {
                    $eventdetails .= " (" . $eventrow['bibleVerse'] . ")";
                }
            }

            $temp_user_id = $ob->userId;

            $roleSql = "SELECT
											r.name,
											r.description
										FROM
											cr_roles r
											INNER JOIN cr_userRoles ur ON r.id = ur.roleId
										  LEFT JOIN cr_eventPeople ep ON ep.userRoleId = ur.id
											LEFT JOIN cr_groups g ON r.groupId = g.id
										WHERE
											ur.userId = '$temp_user_id'
											AND ep.eventId = '$eventId'";

            $roleResult = mysqli_query(db(), $roleSql) or die(mysqli_error(db()));
            $roles = array();
            while ($roleRow = mysqli_fetch_array($roleResult, MYSQLI_ASSOC)) {
                if (($roleRow['name'] == '') || ($roleRow['name'] == $roleRow['description'])):
                    $roles[] = $roleRow['description']; else:
                    $roles[] = $roleRow['description'] . ' - ' . $roleRow['name'];
                endif;
            }

            $updateID = $ob->userId;
            
            $rehearsal = "";/*
            if($row['rehearsal'] == "1") {
                if(($row['eventRehearsal'] == "0") or ($row['eventRehearsalChange'] == "1")) {
                    $rehearsal = $rowSettings['norehearsalemail'];
                } else {
                    //$rehearsal = $rowSettings['yesrehearsal'] . " on " . $rehearsaldate . " at " . $location;
                    $rehearsal = str_replace("[rehearsaldate]", $rehearsaldate, $rowSettings['yesrehearsal']);
                }
            }
            */

            $message = siteSettings()->getNotificationEmail();
            $rotaoutput = implode(', ', $roles);

            $to = $ob->name . ' <' . $ob->email . '>';
            $from = siteSettings()->getOwner() . ' <' . siteSettings()->getAdminEmailAddress() . '>';

            //$subject = "Rota reminder: " . $date;
            $subject = 'You are down for ' . implode(', ', $roles) . " - " . $type . ": ". $date;

            $rotadetails = getEventDetails($eventId, "\r\n", 0, false, "\t");

            $templateFields = [
                'name' => $ob->firstname,
                'date' => $date,
                'location' => $ob->location,
                'rehersal' => $rehearsal,
                'rotaoutput' => $rotaoutput,
                'username' => $ob->username,
                'type' => $type,
                'rotadetails' => $rotadetails,
                'eventdetails' => $eventdetails,
                'comment' => $comment,
            ];

            $message = parseEmailTemplate($message, $templateFields);
            
            $mailOk = sendMail($to, $subject, $message, $from);
            $facebookNotificationOk = createFacebookNotificationForUser($ob->userId, getEventUrl($eventId), $subject);

            if ($mailOk || $facebookNotificationOk) {
                $countarray[] = $ob->userId;
            }
        }
    }

    if (count($countarray) > 0) {
        $sql = "UPDATE 
							cr_eventPeople ep
							INNER JOIN cr_userRoles ur ON ep.userRoleId = ur.id
						SET
							ep.notified = 1
						WHERE
							ep.eventId = '$eventId'
							AND ur.userId IN (".implode(', ', $countarray).")";

        mysqli_query(db(), $sql) or die(mysqli_error(db()));
    }

    
    $sql = "UPDATE
						cr_events e
					SET
						e.notified = e.notified + 1
					WHERE
						e.id = '$eventId'
						AND e.id NOT IN
							(SELECT ep.eventId FROM cr_eventPeople ep WHERE ep.notified = 0 AND ep.eventId = e.id)";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));

    return $countarray;
}





// returns either true, or error message string
function notifyUserForEvent($userId, $eventId, $subject, $message)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $eventId = filter_var($eventId, FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT firstName, lastName, email, recieveReminderEmails FROM cr_users WHERE id = $userId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $user = mysqli_fetch_object($result);


    if (!$user->email) {
        return 'No email address present for '.$user->firstName.' '.$user->lastName;
    }

    if (!$user->recieveReminderEmails) {
        return $user->firstName.' '.$user->lastName.' opted not to recieve reminder emails';
    }

    $name = $user->firstName . ' ' . $user->lastName;

    //line seperator for mails
    //rfc sais \r\n, but this makes trouble in outlook. several spaces plus \n works fine in outlook and thunderbird.
    //spaces to suppress automatic removal of "unnecessary linefeeds" in outlook 2003
    $crlf="      \n";

    setlocale(LC_TIME, siteSettings()->getLangLocale());

    $sql = "SELECT
						e.id,
						e.name,
						e.DATE_FORMAT(date,'%m/%d/%Y %H:%i:%S') AS date,
						e.location,
						e.type,
						e.comment,
						e.bibleVerse,
						e.sermonTitle,
						eg.name AS eventGroup

					FROM 
						cr_events e
						INNER JOIN cr_eventGroups eg ON eg.id = e.eventGroup
					WHERE
						e.id = $eventId
					GROUP BY date,id,location,type,comment";

    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $event = mysqli_fetch_object($result);

    $eventDetails = strftime(siteSettings()->getTimeFormatNormal(), strtotime($event->date));
    $eventDetails .= " - ";
    $eventDetails .= "\r\n";
    $eventDetails .= $event->type;
    $eventDetails .= $event->eventGroup . ': ' . $event->sermonTitle . ' (' . $event->bibleVerse . ')';
    $eventDetails .= "\r\n";
    $eventDetails .= 'Notes: '.$event->comment;
    $eventDetails .= "\r\n";
    $eventDetails .= "\r\n";

    $sql = "SELECT
						r.name,
					FROM 
						cr_roles r
					WHERE
						e.id = $eventId";


    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $roles = mysqli_fetch_object($result);

    $templateValues = [
        'name' => $name,
        'name.first' => $user->firstName,
        'name.last' => $user->lastName,

        'event' => $eventDetails,
        'event.name' => $event->name,
        'event.type' => $event->type,
        'event.location' => $event->location,
        'event.comment' => $event->comment,

        'event.sermon' => $event->eventGroup . ': ' . $event->sermonTitle . ' (' . $event->bibleVerse . ')',
        'event.sermon.name' => $event->sermonTitle,
        'event.sermon.series' => $event->eventGroup,
        'event.sermon.verse' => $event->bibleVerse,

        'roles' => $roles, // legacy
        'month' => $overviewMonth,
        'year' => $overviewYear,
    ];

    $message = parseEmailTemplate($message, $templateValues);

    //$message = str_replace("\r\n",$crlf,$message);

    $to = $name . ' <' . $user->email . '>';
    $from = siteSettings()->getOwner() . ' <' . $siteSettings()->getAdminEmailAddress() . '>';

    $sent = false;
    $sent = sendMail($to, $subject, $message, $from);

    if ($sent == true) {
        return true;
    } else {
        return "Error while sending mail to ".$user->firstName.' '.$user->lastName;
    }
    
    return 'Unknown error for '.$user->firstName.' '.$user->lastName;
}





function getEventEmailSubject($eventId)
{
    return "";
}




function getEventEmailMessage($eventId)
{
    return "";
}





// returns true - or array of error message strings
function sendEventEmailToGroups($eventId, $subject, $message, $groups)
{
    if (!isset($groups)) {
        return ['No emails sent: Group(s) not found'];
    }

    $groupsString = implode(', ', $groups);

    $sql = "SELECT
						DISTINCT u.id AS userId
					FROM
						cr_users u
						INNER JOIN cr_userRoles ur ON u.id = ur.userId
						INNER JOIN cr_roles r ON r.id = ur.roleId
						INNER JOIN cr_groups g ON g.id = r.groupId
						INNER JOIN cr_eventPeople ep ON ep.userRoleId = ur.id
					WHERE
						g.id IN ($groupsString)
						AND ep.eventId = $eventId";

    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $errors = [];
    while ($ob = mysqli_fetch_object($result)) {
        $status = sendEventMessageEmailToUser($ob->userId, $eventId, $subject, $message);
        if ($status !== true) {
            $errors[] = $status;
        }
    }

    $sql = "SELECT name FROM cr_groups WHERE id IN ($groupsString)";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $groupNames = '';
    while ($group = mysqli_fetch_object($result)) {
        $groupNames .= $group->name.', ';
    }

    if (isset($errors)) {
        createNotificationForUser($_SESSION['userid'], 'Email sent with warnings', 'Email sent to '.$groupNames.' groups with errors: '.implode(', ', $errors), 'email');
        return $errors;
    }

    createNotificationForUser($_SESSION['userid'], 'Group email sent', 'Email sent successfully to '.$groupNames, 'email');
    return $true;
}





function getGroupEmailSubject()
{
    return "Upcoming Rota";
}




function getGroupEmailMessage()
{
    $sql = "SELECT overviewemail FROM cr_settings";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    return $ob->overviewemail;
}




// returns true - or array of error message strings
function sendGroupEmail($subject, $message, $groups)
{
    if (!isset($groups)) {
        return ['No emails sent: Group(s) not found'];
    }

    $groupsString = implode(', ', $groups);

    $sql = "SELECT
						DISTINCT u.id AS userId
					FROM
						cr_users u
						INNER JOIN cr_userRoles ur ON u.id = ur.userId
						INNER JOIN cr_roles r ON r.id = ur.roleId
						INNER JOIN cr_groups g ON g.id = r.groupId
					WHERE
						g.id IN ($groupsString)
						";


    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $errors = [];
    while ($ob = mysqli_fetch_object($result)) {
        $status = sendUpcomingEventsToUser($ob->userId, $subject, $message);
        if ($status !== true) {
            $errors[] = $status;
        }
    }

    $sql = "SELECT name FROM cr_groups WHERE id IN ($groupsString)";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $groupNames = '';
    while ($group = mysqli_fetch_object($result)) {
        $groupNames .= $group->name.', ';
    }

    if (isset($errors)) {
        createNotificationForUser($_SESSION['userid'], 'Email sent with warnings', 'Email sent to '.$groupNames." groups with errors: \n- ".implode("\n- ", $errors), 'email');
        return $errors;
    }

    createNotificationForUser($_SESSION['userid'], 'Group email sent', 'Email sent successfully to '.$groupNames, 'email');
    return $true;
}




// returns either true, or error message string
function sendEventMessageEmailToUser($userId, $eventId, $subject, $message)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $eventId = filter_var($eventId, FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT firstName, lastName, email, isOverviewRecipient FROM cr_users WHERE id = $userId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $user = mysqli_fetch_object($result);


    if (!$user->email) {
        return 'No email address present for '.$user->firstName.' '.$user->lastName;
    }

    if (!$user->isOverviewRecipient) {
        return $user->firstName.' '.$user->lastName.' opted not to recieve group emails';
    }

    $name = $user->firstName . ' ' . $user->lastName;

    //line seperator for mails
    //rfc sais \r\n, but this makes trouble in outlook. several spaces plus \n works fine in outlook and thunderbird.
    //spaces to suppress automatic removal of "unnecessary linefeeds" in outlook 2003
    $crlf="      \n";

    $siteadmin = siteSettings()->getAdminEmailAddress();

    $sql = "SELECT
						e.id,
						e.date,
						l.name AS location,
						et.name AS type,
						e.comment,
						group_concat(CONCAT(r.name, ': ', u.firstname,' ',u.lastname) SEPARATOR ', ') AS roles
					FROM
						cr_events e
						INNER JOIN cr_eventPeople ep ON ep.eventId = e.id
						INNER JOIN cr_userRoles ur ON ur.id = ep.userRoleId
						INNER JOIN cr_users u ON u.id = ur.userId
						INNER JOIN cr_roles r ON r.id = ur.roleId
						INNER JOIN cr_locations l ON l.id = e.location
						INNER JOIN cr_eventTypes et ON et.id = e.type
					WHERE
						e.id = $eventId
						AND e.removed = 0
					GROUP BY
						e.id";

    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    setlocale(LC_TIME, siteSettings()->getLangLocale()); //de_DE

    $eventDetails = "";
    $date;
    $ob = mysqli_fetch_object($result);
    
    $date = $ob->date;

    $eventDetails .= strftime(siteSettings()->getTimeFormatNormal(), strtotime($ob->date));
    $eventDetails .= " - ";
    $eventDetails .= $ob->type;
    $eventDetails .= "\r\n";

    $eventDetails .= $ob->roles;
    $eventDetails .= "\r\n";
    $eventDetails .= 'Notes: '.$ob->comment;
    $eventDetails .= "\r\n";
    $eventDetails .= "\r\n";

    
    $overviewMonth = strtoupper(strftime("%B", strtotime($date)));
    $overviewYear = strftime("%Y", strtotime($date));
    
    $templateValues = [
        'name' => $name,
        'event' => $eventDetails,
        'roles' => $roles,
        'month' => $overviewMonth,
        'year' => $overviewYear,
    ];

    $message = parseEmailTemplate($message, $templateValues);

    //$message = str_replace("\r\n",$crlf,$message);

    $to = $name . ' <' . $user->email . '>';
    $from = siteSettings()->getOwner() . ' <' . $siteadmin . '>';

    $sent = false;
    $sent = sendMail($to, $subject, $message, $from);

    if ($sent == true) {
        return true;
    } else {
        return "Error while sending mail to ".$user->firstName.' '.$user->lastName;
    }
    
    return 'Unknown error for '.$user->firstName.' '.$user->lastName;
}





// returns either true, or error message string
function sendUpcomingEventsToUser($userId, $subject, $message)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT firstName, lastName, email, isOverviewRecipient FROM cr_users WHERE id = $userId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $user = mysqli_fetch_object($result);


    if (!$user->email) {
        return 'No email address present for '.$user->firstName.' '.$user->lastName;
    }

    if (!$user->isOverviewRecipient) {
        return $user->firstName.' '.$user->lastName.' opted not to recieve group emails';
    }

    $name = $user->firstName . ' ' . $user->lastName;

    //line seperator for mails
    //rfc sais \r\n, but this makes trouble in outlook. several spaces plus \n works fine in outlook and thunderbird.
    //spaces to suppress automatic removal of "unnecessary linefeeds" in outlook 2003
    $crlf="      \n";

    $sql = "SELECT lang_locale, time_format_normal, adminemailaddress, owner FROM cr_settings";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    //siteSettings() = mysqli_fetch_object($result);
    // todo: fix settings in this function

    $lang_locale = siteSettings()->getLangLocale();
    $siteadmin = siteSettings()->getAdminEmailAddress();

    $sql = "SELECT
						e.id
					FROM
						cr_events e
						INNER JOIN cr_eventPeople ep ON ep.eventId = e.id
						INNER JOIN cr_userRoles ur ON ur.id = ep.userRoleId
					WHERE
						ur.userId = $userId
						AND e.date >= CURRENT_DATE()
						AND e.removed = 0";
    
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $events;
    while ($ob = mysqli_fetch_object($result)) {
        $events[] = $ob->id;
    }

    if (!isset($events)) {
        return $name . ' has no events in the upcoming rota';
    }

    $events = implode(', ', $events);

    $sql = "SELECT
					id,
					DATE_FORMAT(date,'%m/%d/%Y %H:%i:%S') AS date,
					location,
					type,
					comment,
					group_concat(rota separator '\r\n') AS joinedskills
				FROM (
					SELECT
						e.id,
						e.date,
						l.name AS location,
						et.name AS type,
						e.comment,
						r.id AS roleId,
						r.name AS role,
						CONCAT(u.firstname,' ',u.lastname) as name,
						CONCAT(r.name, ': ', u.firstname,' ',u.lastname) as rota
					FROM
						cr_eventPeople ep
						INNER JOIN cr_events e ON e.id = ep.eventId
						INNER JOIN cr_userRoles ur ON ur.id = ep.userRoleId
						INNER JOIN cr_roles r ON r.id = ur.roleId
						INNER JOIN cr_users u ON u.id = ur.userId
						INNER JOIN cr_locations l ON l.id = e.location
						INNER JOIN cr_eventTypes et ON et.id = e.type
					WHERE
						(
							e.id IN ($events)
						)
					ORDER BY date ASC, role DESC
				) sub
				GROUP BY date,id,location,type,comment";

    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    setlocale(LC_TIME, $lang_locale); //de_DE

    $eventDetails = "";
    $date;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $date = $row['date'];

        $eventDetails .= strftime(siteSettings()->getTimeFormatNormal(), strtotime($row['date']));
        $eventDetails .= " - ";
        $eventDetails .= $row['type'];
        $eventDetails .= "\r\n";

        $eventDetails .= $row['joinedskills'];
        $eventDetails .= "\r\n";
        $eventDetails .= 'Notes: '.$row['comment'];
        $eventDetails .= "\r\n";
        $eventDetails .= "\r\n";
    }
    $overviewMonth = strtoupper(strftime("%B", strtotime($date)));
    $overviewYear = strftime("%Y", strtotime($date));
    
    $message = str_replace("[NAME]", $name, $message);
    $message = str_replace("[EVENTS]", $eventDetails, $message);
    $message = str_replace("[MONTH]", $overviewMonth, $message);
    $message = str_replace("[YEAR]", $overviewYear, $message);

    //$message = emailTemplate($message, $name, $date, $location, $rehearsal, $rotaoutput, $username, $siteurl);

    //$message = str_replace("\r\n",$crlf,$message);

    $to = $name . ' <' . $user->email . '>';
    $from = siteSettings()->getOwner() . ' <' . $siteadmin . '>';

    $sent = false;
    $sent = sendMail($to, $subject, $message, $from);

    if ($sent == true) {
        return true;
    } else {
        return "Error while sending mail to ".$user->firstName.' '.$user->lastName;
    }
    
    return 'Unknown error for '.$user->firstName.' '.$user->lastName;
}





function notifyAttack($fileName, $attackType, $attackerID)
{
    $sql = "SELECT `siteurl`,
	`adminemailaddress` AS `siteadmin`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = $attackerID) AS `name`,
	Now() AS `attackTime`
	FROM cr_settings";

    $userresult = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    while ($row = mysqli_fetch_array($userresult, MYSQLI_ASSOC)) {
        $subject = "SECURITY-ALERT - Attack blocked successfully";
        $message =  "Type:\t\t " . $attackType . "<br>\r\n" .
                    "Attacker:\t " . $row[name] . "<br>\r\n" .
                    "Date:\t\t " . date("Y-m-d H:i:s") . "<br>\r\n" .
                    "Script:\t\t " . $fileName . "\r\n " . "<br>\r\n" ;

        //$headers = 'From: ' . $row['siteadmin'] . "\r\n" .
        //'Reply-To: ' . $row['siteadmin'] . "\r\n";

        $to = $row['siteadmin'];

        //mail($to, $subject, strip_tags($message), $headers);
        sendMail($to, $subject, strip_tags($message), $to);

        echo $subject . "<br><br>";
        echo $message . "<br>";
        echo "An email about this incident was sent to administrator!";
    }
}





function notifyInfo($fileName, $infoMsg, $userID)
{
    $sql = "SELECT `siteurl`,
	`adminemailaddress` AS `siteadmin`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM cr_users WHERE `cr_users`.id = $userID) AS `name`
	FROM cr_settings";

    $userresult = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    while ($row = mysqli_fetch_array($userresult, MYSQLI_ASSOC)) {
        $subject = "Info - " . $infoMsg . " - " . $row[name] ;
        $message =  "Type:\t\t " . $infoMsg . "<br>\r\n" .
                    "User:\t\t " . $row[name] . "<br>\r\n" .
                    "Date:\t\t " . date("Y-m-d H:i:s") . "<br>\r\n" .
                    "Script:\t\t " . $fileName . "\r\n " . "<br>\r\n" ;

        //$headers = 'From: ' . $row['siteadmin'] . "\r\n" .
        //'Reply-To: ' . $row['siteadmin'] . "\r\n";

        $to = $row['siteadmin'];

        //mail($to, $subject, strip_tags($message), $headers);
        sendMail($to, $subject, strip_tags($message), $to);
    }
}




function splitSubjectMessage($defaultSubject, $message)
{
    if (preg_match("/(\{\{)((.)+){1}(\}\})/", $message, $matches)==1) {
        $defaultSubject = $matches[2];
            //$subject = str_replace(array("{{","}}"),"",$matches[0]);
            $message = str_replace($matches[1].$matches[2].$matches[4], "", $message);
            //$message = $matches[4];

        //$message = $message . "\r\n\r\n";
        //$message = $message . "m0 ". $matches[0] . "\r\n";
        //$message = $message . "m1 ". $matches[1] . "\r\n";
        //$message = $message . "m2 ". $matches[2] . "\r\n";
        //$message = $message . "m3 ". $matches[3] . "\r\n";
        //$message = $message . "m4 ". $matches[4] . "\r\n";
        //$message = $message . "m5 ". $matches[5] . "\r\n";
        //$message = $message . "m6 ". $matches[6] . "\r\n";
        //$message = $message . "m7 ". $matches[7] . "\r\n";
        //$message = $message . "m8 ". $matches[8] . "\r\n";
        //$message = $message . "m9 ". $matches[9] . "\r\n";
        //$message = $message . "m10 ". $matches[10] . "\r\n";
    }
    return array($defaultSubject,$message);
}



function mailToDb($to, $subject, $message, $from, $bcc = "")
{
    $to = mysqli_real_escape_string(db(), $to);
    $subject = mysqli_real_escape_string(db(), $subject);
    $message = mysqli_real_escape_string(db(), $message);
    $from = mysqli_real_escape_string(db(), $from);
    $bcc = mysqli_real_escape_string(db(), $bcc);

    $sql = "INSERT INTO cr_emails (emailTo, emailBcc, emailFrom, subject, message) VALUES ('$to', '$bcc', '$from', '$subject', '$message')";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));
    
    return mysqli_insert_id(db());
}



function logFailedMailWithId($id, $error)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $error = mysqli_real_escape_string(db(), $error);

    $sql = "UPDATE cr_emails SET error = '$error' WHERE id = '$id'";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));
}
