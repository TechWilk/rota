<?php

namespace TechWilk\Rota;

// legacy code support - do not use in new code
function addPeople($eventId, $userRoleId)
{
    addUserToEvent($eventId, $userRoleId);
}

// replaces addPeople();
function addUserToEvent($eventId, $userRoleId)
{
    $sql = 'INSERT INTO eventPeople (eventId, userRoleId) VALUES (?, ?)';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $eventId, $userRoleId);

    if (!mysqli_stmt_execute($stmt)) {
        exit('Error: '.mysqli_error(db()));
    }
    mysqli_stmt_close($stmt);
}

// legacy code support - do not use in new code
function removeEventMember($id, $userRoleId)
{
    removeUserFromEvent($id, $userRoleId);
}

// replaces removeEventMember();
function removeUserFromEvent($id, $userRoleId)
{
    $sql = 'DELETE FROM eventPeople WHERE eventId = ? AND userRoleId = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $id, $userRoleId);

    if (!mysqli_stmt_execute($stmt)) {
        exit('Error: '.mysqli_error(db()));
    }
    mysqli_stmt_close($stmt);
}

function createSwapEntry($eventPersonId, $newUserRoleId, $verified = 0)
{
    $eventPersonId = filter_var($eventPersonId, FILTER_SANITIZE_NUMBER_INT);
    $newUserRoleId = filter_var($newUserRoleId, FILTER_SANITIZE_NUMBER_INT);

    $sql = 'SELECT userRoleId FROM eventPeople WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $eventPersonId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $oldUserRoleId = $row['userRoleId'];
    }
    mysqli_stmt_close($stmt);

    if ($verified) {
        $verified = 1;
        $declined = 0;

        $sql = 'UPDATE eventPeople SET userRoleId = ?, notified = 0, deleted = 0 WHERE id = ?';
        $stmt = mysqli_prepare(db(), $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $newUserRoleId, $eventPersonId);
        mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
        mysqli_stmt_close($stmt);
    } else {
        $verified = 0;
        $declined = 0;
    }

    $verificationCode = mysqli_real_escape_string(db(), RandomPassword(18, true, true, true));
    $userId = $_SESSION['userid'];

    $swap = new Swap();
    $swap->setEventPersonId($eventPersonId);
    $swap->setOldUserRoleId($oldUserRoleId);
    $swap->setNewUserRoleId($newUserRoleId);
    $swap->setRequestedBy($userId);
    $swap->setUpdated('now');

    $swap->setVerificationCode($verificationCode);

    $swap->save();

    notifySwapCreated($swap->getId());

    return $swap->getId();
}

function verificationCodeForSwap($swapId)
{
    $sql = 'SELECT verificationCode FROM swaps WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $swapId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $verificationCode = $row['verificationCode'];
    }
    mysqli_stmt_close($stmt);

    return $verificationCode;
}

function acceptSwap($swapId)
{
    if (empty($swapId)) {
        return 0;
    }

    $swapId = filter_var($swapId, FILTER_SANITIZE_NUMBER_INT);
    $swap = SwapQuery::create()->findPk($swapId);

    if (!$swap->getEventPersonId()) {
        return 0;
    } elseif ($swap->getAccepted()) {
        return 2;
    } elseif ($swap->getDeclined()) {
        return 3;
    } else {
        $swap->setAccepted(true);
        $swap->save();

        $eventPerson = $swap->getEventPerson();
        $eventPerson->setUserRoleId($swap->getNewUserRoleId());
        $eventPerson->save();

        notifySwapAccepted($swapId);
        // send mail to user who requested swap
    }

    return 1;
}

function declineSwap($swapId)
{
    if (empty($swapId)) {
        return 0;
    }

    $swapId = filter_var($swapId, FILTER_SANITIZE_NUMBER_INT);
    $swap = SwapQuery::create()->findPk($swapId);

    if (!$swap->getEventPersonId()) {
        return 0;
    } elseif ($swap->getAccepted()) {
        return 2;
    } elseif ($swap->getDeclined()) {
        return 3;
    } else {
        $swap->setDeclined(true);
        $swap->save();

        notifySwapDeclined($swapId);
        // send mail to user who requested swap
    }

    return 1;
}

function notifySwapCreated($swapId, $message = '')
{
    $query = "SELECT `siteurl`,
	`adminemailaddress` AS `siteadmin`,
	`time_format_long`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldUserName`,
	(SELECT firstname FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldUserFirstName`,
	(SELECT u.id FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldUserId`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newUserName`,
	(SELECT firstname FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newUserFirstName`,
	(SELECT u.id FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newUserId`,
	(SELECT r.name FROM roles r INNER JOIN userRoles ur ON ur.roleId = r.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldRole`,
	(SELECT r.name FROM roles r INNER JOIN userRoles ur ON ur.roleId = r.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newRole`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM users u INNER JOIN swaps sw ON sw.requestedBy = u.id WHERE sw.id = ?) AS `requestedBy`,
	(SELECT e.date FROM events e INNER JOIN eventPeople ep ON e.id = ep.eventId INNER JOIN swaps sw ON sw.eventPersonId = ep.id WHERE sw.id = ?) AS `eventDate`
	FROM settings";

    $stmt = mysqli_prepare(db(), $query);
    mysqli_stmt_bind_param($stmt, 'iiiiiiiiii', $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    $verificationCode = verificationCodeForSwap($swapId);

    $subject = 'Swap requested: '.$ob->newUserName.' - '.$ob->eventDate;
    $message = "Date:\t\t ".strftime($ob->time_format_long, strtotime($ob->eventDate))."\r\n".
                "Requested by:\t\t ".$ob->requestedBy."\r\n".
                "Swap from:\t\t ".$ob->oldUserName.' - '.$ob->oldRole."\r\n".
                "Swap to:\t\t ".$ob->newUserName.' - '.$ob->newRole."\r\n".
                "Accept:\t\t ".$ob->siteurl.'/swap.php?action='.'accept&swap='.$swapId.'&verify='.$verificationCode."\r\n "."\r\n".
                "Decline:\t\t ".$ob->siteurl.'/swap.php?action='.'decline&swap='.$swapId.'&verify='.$verificationCode."\r\n "."\r\n";

    $from = $ob->siteadmin;

    $linkToSwap = 'swap.php?swap='.$swapId;

    $sessionUserId = $_SESSION['userid'];

    if ($sessionUserId == $ob->oldUserId) {    // if old user requests swap
        // notify new user
        $notificationMessage = $ob->oldUserFirstName.' requested a swap';
        $notificationId = createNotificationForUser($ob->newUserId, $notificationMessage, $message, 'swap', $linkToSwap);
        createFacebookNotificationForUser($ob->newUserId, 'notification.php?id='.$notificationId, $notificationMessage);
        sendMail(getEmailWithId($ob->newUserId), $subject, $message, $from);
    } elseif ($sessionUserId == $ob->newUserId) {    // if new user requests swap
        // notify old user
        $notificationMessage = $ob->newUserFirstName.' requested a swap';
        $notificationId = createNotificationForUser($ob->oldUserId, $notificationMessage, $message, 'swap', $linkToSwap);
        createFacebookNotificationForUser($ob->oldUserId, 'notification.php?id='.$notificationId, $notificationMessage);
        sendMail(getEmailWithId($ob->oldUserId), $subject, $message, $from);
    } else { // notify both users
        $sessionUserFirstName = getFirstNameWithId($sessionUserId);
        $notificationMessageOldUser = $sessionUserFirstName.' requested a swap for '.$ob->newUserFirstName;
        $notificationMessageNewUser = $sessionUserFirstName.' requested a swap for '.$ob->oldUserFirstName;
        $notificationIdOldUser = createNotificationForUser($ob->oldUserId, $notificationMessageOldUser, $message, 'swap', $linkToSwap);
        $notificationIdNewUser = createNotificationForUser($ob->newUserId, $notificationMessageNewUser, $message, 'swap', $linkToSwap);
        createFacebookNotificationForUser($ob->oldUserId, 'notification.php?id='.$notificationIdOldUser, $notificationMessageOldUser);
        createFacebookNotificationForUser($ob->newUserId, 'notification.php?id='.$notificationIdNewUser, $notificationMessageNewUser);
        sendMail(getEmailWithId($ob->oldUserId), $subject, $message, $from);
        sendMail(getEmailWithId($ob->newUserId), $subject, $message, $from);
    }
}

function notifySwapAccepted($swapId, $message = '')
{
    $query = "SELECT `siteurl`,
	`adminemailaddress` AS `siteadmin`,
	`time_format_long`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldUserName`,
	(SELECT firstname FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldUserFirstName`,
	(SELECT u.id FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldUserId`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newUserName`,
	(SELECT firstname FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newUserFirstName`,
	(SELECT u.id FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newUserId`,
	(SELECT u.email FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.requestedBy = ur.id WHERE sw.id = ?) AS `email`,
	(SELECT r.name FROM roles r INNER JOIN userRoles ur ON ur.roleId = r.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldRole`,
	(SELECT r.name FROM roles r INNER JOIN userRoles ur ON ur.roleId = r.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newRole`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM users u INNER JOIN swaps sw ON sw.requestedBy = u.id WHERE sw.id = ?) AS `requestedBy`,
	(SELECT e.date FROM events e INNER JOIN eventPeople ep ON e.id = ep.eventId INNER JOIN swaps sw ON sw.eventPersonId = ep.id WHERE sw.id = ?) AS `eventDate`
	FROM settings";

    $stmt = mysqli_prepare(db(), $query);
    mysqli_stmt_bind_param($stmt, 'iiiiiiiiiii', $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    $verificationCode = verificationCodeForSwap($swapId);

    $subject = 'Swap accepted: '.$ob->newUserName.' - '.$ob->eventDate;
    $message = "The following swap has been accepted\r\n\r\n".
                "Date:\t\t ".strftime($ob->time_format_long, strtotime($ob->eventDate))."\r\n".
                "Requested by:\t\t ".$ob->requestedBy."\r\n".
                "Swap from:\t\t ".$ob->oldUserName.' - '.$ob->oldRole."\r\n".
                "Swap to:\t\t ".$ob->newUserName.' - '.$ob->newRole."\r\n";
    $from = $ob->siteadmin;

    $linkToSwap = 'swap.php?swap='.$swapId;

    $sessionUserId = $_SESSION['userid'];

    if ($sessionUserId == $ob->oldUserId) {    // if old user accepted swap
        // notify new user
        $notificationMessage = $ob->oldUserFirstName.' accepted a swap';
        $notificationId = createNotificationForUser($ob->newUserId, $notificationMessage, $message, 'swap', $linkToSwap);
        createFacebookNotificationForUser($ob->newUserId, 'notification.php?id='.$notificationId, $notificationMessage);
        sendMail(getEmailWithId($ob->newUserId), $subject, $message, $from);
    } elseif ($sessionUserId == $ob->newUserId) {    // if new user accepted swap
        // notify old user
        $notificationMessage = $ob->newUserFirstName.' accepted a swap';
        $notificationId = createNotificationForUser($ob->oldUserId, $notificationMessage, $message, 'swap', $linkToSwap);
        createFacebookNotificationForUser($ob->oldUserId, 'notification.php?id='.$notificationId, $notificationMessage);
        sendMail(getEmailWithId($ob->oldUserId), $subject, $message, $from);
    } elseif (isset($_GET['token'])) { // if accepted by email URL with token
        // notify whoever requested swap
        if ($ob->newUserId == $ob->requestedBy) {
            $notificationMessage = 'Swap accepted with '.$ob->oldUserFirstName;
            $notificationId = createNotificationForUser($ob->newUserId, $notificationMessage, $message, 'swap', $linkToSwap);
            createFacebookNotificationForUser($ob->newUserId, 'notification.php?id='.$notificationId, $notificationMessage);
            sendMail(getEmailWithId($ob->newUserId), $subject, $message, $from);
        } elseif ($ob->oldUserId == $ob->requestedBy) {
            $notificationMessage = 'Swap accepted with '.$ob->newUserFirstName;
            $notificationId = createNotificationForUser($ob->oldUserId, $notificationMessage, $message, 'swap', $linkToSwap);
            createFacebookNotificationForUser($ob->oldUserId, 'notification.php?id='.$notificationId, $notificationMessage);
            sendMail(getEmailWithId($ob->oldUserId), $subject, $message, $from);
        } else { // notify both
            $notificationMessageOldUser = 'Swap accepted with '.$ob->newUserFirstName;
            $notificationMessageNewUser = 'Swap accepted with '.$ob->oldUserFirstName;
            $notificationIdOldUser = createNotificationForUser($ob->oldUserId, $notificationMessageOldUser, $message, 'swap', $linkToSwap);
            $notificationIdNewUser = createNotificationForUser($ob->newUserId, $notificationMessageNewUser, $message, 'swap', $linkToSwap);
            createFacebookNotificationForUser($ob->oldUserId, 'notification.php?id='.$notificationIdOldUser, $notificationMessage);
            createFacebookNotificationForUser($ob->newUserId, 'notification.php?id='.$notificationIdNewUser, $notificationMessage);
            sendMail(getEmailWithId($ob->oldUserId), $subject, $message, $from);
            sendMail(getEmailWithId($ob->newUserId), $subject, $message, $from);
        }
    } else { // notify both users
        $sessionUserFirstName = getFirstNameWithId($sessionUserId);
        $notificationMessageOldUser = $sessionUserFirstName.' accepted a swap for '.$ob->newUserFirstName;
        $notificationMessageNewUser = $sessionUserFirstName.' accepted a swap for '.$ob->oldUserFirstName;
        $notificationIdOldUser = createNotificationForUser($ob->oldUserId, $notificationMessageOldUser, $message, 'swap', $linkToSwap);
        $notificationIdNewUser = createNotificationForUser($ob->newUserId, $notificationMessageNewUser, $message, 'swap', $linkToSwap);
        createFacebookNotificationForUser($ob->oldUserId, 'notification.php?id='.$notificationIdOldUser, $notificationMessage);
        createFacebookNotificationForUser($ob->newUserId, 'notification.php?id='.$notificationIdNewUser, $notificationMessage);
        sendMail(getEmailWithId($ob->oldUserId), $subject, $message, $from);
        sendMail(getEmailWithId($ob->newUserId), $subject, $message, $from);
    }
}

function notifySwapDeclined($swapId, $message = '')
{
    $query = "SELECT `siteurl`,
	`adminemailaddress` AS `siteadmin`,
	`time_format_long`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldUserName`,
	(SELECT firstname FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldUserFirstName`,
	(SELECT u.id FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldUserId`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newUserName`,
	(SELECT firstname FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newUserFirstName`,
	(SELECT u.id FROM users u INNER JOIN userRoles ur ON ur.userId = u.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newUserId`,
	(SELECT r.name FROM roles r INNER JOIN userRoles ur ON ur.roleId = r.id INNER JOIN swaps sw ON sw.oldUserRoleId = ur.id WHERE sw.id = ?) AS `oldRole`,
	(SELECT r.name FROM roles r INNER JOIN userRoles ur ON ur.roleId = r.id INNER JOIN swaps sw ON sw.newUserRoleId = ur.id WHERE sw.id = ?) AS `newRole`,
	(SELECT CONCAT(`firstname`, ' ', `lastname`) FROM users u INNER JOIN swaps sw ON sw.requestedBy = u.id WHERE sw.id = ?) AS `requestedBy`,
	(SELECT e.date FROM events e INNER JOIN eventPeople ep ON e.id = ep.eventId INNER JOIN swaps sw ON sw.eventPersonId = ep.id WHERE sw.id = ?) AS `eventDate`
	FROM settings";

    $stmt = mysqli_prepare(db(), $query);
    mysqli_stmt_bind_param($stmt, 'iiiiiiiiii', $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId, $swapId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    $verificationCode = verificationCodeForSwap($swapId);

    $subject = 'Swap declined: '.$ob->newUserName.' - '.$ob->eventDate;
    $message = "The following swap has been declined\r\n\r\n".
                "Date:\t\t ".strftime($ob->time_format_long, strtotime($ob->eventDate))."\r\n".
                "Requested by:\t\t ".$ob->requestedBy."\r\n".
                "Swap from:\t\t ".$ob->oldUserName.' - '.$ob->oldRole."\r\n".
                "Swap to:\t\t ".$ob->newUserName.' - '.$ob->newRole."\r\n";

    $from = $ob->siteadmin;

    $linkToSwap = 'swap.php?swap='.$swapId;

    $sessionUserId = $_SESSION['userid'];

    if ($sessionUserId == $ob->oldUserId) {    // if old user declines swap
        // notify new user
        $notificationMessage = $ob->oldUserFirstName.' declined a swap';
        $notificationId = createNotificationForUser($ob->newUserId, $notificationMessage, $message, 'swap', $linkToSwap);
        createFacebookNotificationForUser($ob->newUserId, 'notification.php?id='.$notificationId, $notificationMessage);
        sendMail(getEmailWithId($ob->newUserId), $subject, $message, $from);
    } elseif ($sessionUserId == $ob->newUserId) {    // if new user declines swap
        // notify old user
        $notificationMessage = $ob->newUserFirstName.' declined a swap';
        $notificationId = createNotificationForUser($ob->oldUserId, $notificationMessage, $message, 'swap', $linkToSwap);
        createFacebookNotificationForUser($ob->newUserId, 'notification.php?id='.$notificationId, $notificationMessage);
        sendMail(getEmailWithId($ob->oldUserId), $subject, $message, $from);
    } else { // notify both users
        $sessionUserFirstName = getFirstNameWithId($sessionUserId);
        $notificationMessageOldUser = $sessionUserFirstName.' declined a swap for '.$ob->newUserFirstName;
        $notificationMessageNewUser = $sessionUserFirstName.' declined a swap for '.$ob->oldUserFirstName;
        $notificationIdOldUser = createNotificationForUser($ob->oldUserId, $notificationMessageOldUser, $message, 'swap', $linkToSwap);
        $notificationIdNewUser = createNotificationForUser($ob->newUserId, $notificationMessageNewUser, $message, 'swap', $linkToSwap);
        createFacebookNotificationForUser($ob->oldUserId, 'notification.php?id='.$notificationIdOldUser, $notificationMessage);
        createFacebookNotificationForUser($ob->newUserId, 'notification.php?id='.$notificationIdNewUser, $notificationMessage);
        sendMail(getEmailWithId($ob->newUserId), $subject, $message, $from);
        sendMail(getEmailWithId($ob->oldUserId), $subject, $message, $from);
    }
}

function canDeclineSwap($swapId)
{
    $userId = $_SESSION['userid'];

    $sql = 'SELECT
					id,
					sw.requestedBy,
					(SELECT ur.userId FROM userRoles ur WHERE ur.id = sw.oldUserRoleId) AS oldUser,
					(SELECT ur.userId FROM userRoles ur WHERE ur.id = sw.newUserRoleId) AS newUser
					FROM swaps sw
					WHERE sw.id = ?
						AND sw.declined = 0
						AND sw.accepted = 0';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $swapId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $swap = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    if (!isset($swap->id)) {
        return false;
    }

    if (isAdmin()) {
        return true;
    }

    if ($userId == $swap->requestedBy || $userId == $swap->oldUser || $userId == $swap->newUser) {
        return true;
    }

    return false;
}

function canAcceptSwap($swapId)
{
    $userId = $_SESSION['userid'];

    $sql = 'SELECT
					id,
					sw.requestedBy,
					(SELECT ur.userId FROM userRoles ur WHERE ur.id = sw.oldUserRoleId) AS oldUser,
					(SELECT ur.userId FROM userRoles ur WHERE ur.id = sw.newUserRoleId) AS newUser
					FROM swaps sw
					WHERE sw.id = ?
						AND sw.declined = 0
						AND sw.accepted = 0';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $swapId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $swap = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    if (!isset($swap->id)) {
        return false;
    }

    if (isAdmin()) {
        return true;
    }

    if ($userId != $swap->requestedBy && ($userId == $swap->oldUser || $userId == $swap->newUser)) {
        return true;
    }

    return false;
}

function swapDetailsWithId($swapId)
{
    $swapId = filter_var($swapId, FILTER_SANITIZE_NUMBER_INT);

    $sql = 'SELECT * FROM swaps WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $swapId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    return $ob;
}

function rolesOfUserAtEvent($userId, $eventId)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $eventId = filter_var($eventId, FILTER_SANITIZE_NUMBER_INT);

    $sql = 'SELECT
						ep.id AS eventPersonId,
						ep.eventId,
						ep.userRoleId,
						ep.notified,
						ur.roleId,
						ur.userId
					FROM
						eventPeople ep
						INNER JOIN userRoles ur ON ur.id = ep.userRoleId
					WHERE
						eventId = ?
						AND userId = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $eventId, $userId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);

    while ($entry = mysqli_fetch_object($result)) {
        $a[] = $entry;
    }
    mysqli_stmt_close($stmt);

    return $a;
}

function rolesUserCanCoverAtEvent($userId, $eventId)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $eventId = filter_var($eventId, FILTER_SANITIZE_NUMBER_INT);

    $sql = 'SELECT
						ep.id AS eventPersonId,
						ep.eventId,
						ep.userRoleId,
						ep.notified,
						ur.roleId,
						ur.userId,
						(SELECT id FROM userRoles WHERE roleId = ur.roleId AND userId = ?) AS newUserRoleId
					FROM
						eventPeople ep
						INNER JOIN userRoles ur ON ur.id = ep.userRoleId
					WHERE
						eventId = ?
						AND ur.roleId IN (SELECT roleId FROM userRoles WHERE userId = ?)';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $userId, $eventId, $userId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);

    while ($entry = mysqli_fetch_object($result)) {
        $a[] = $entry;
    }
    mysqli_stmt_close($stmt);

    return $a;
}

function numberOfRolesOfUserAtEvent($userId, $eventId)
{
    $sql = 'SELECT COUNT(ur.userId) AS numberOfRoles FROM eventPeople ep INNER JOIN userRoles ur ON ur.id = ep.userRoleId WHERE eventId = ? AND userId = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $eventId, $userId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    return $ob->numberOfRoles;
}

function removeSeries($seriesId)
{
    $seriesId = filter_var($seriesId, FILTER_SANITIZE_NUMBER_INT);

    $sql = 'UPDATE eventGroups SET archived = true WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $seriesId);
    if (!mysqli_stmt_execute($stmt)) {
        exit('Error: '.mysqli_error(db()));
    }
    mysqli_stmt_close($stmt);
}

function addPeopleBand($bandId, $userRoleId)
{
    $sql = 'INSERT INTO bandMembers (bandId, userRoleId) VALUES (?, ?)';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $bandId, $userRoleId);

    if (!mysqli_stmt_execute($stmt)) {
        exit('Error: '.mysqli_error(db()));
    }
    mysqli_stmt_close($stmt);
}

function getEventUrl($eventId)
{
    return 'event.php?id='.$eventId;
}

function getEventDetails($eventID, $separator, $type = 4, $apprev_description = true, $prefix = '')
{
    //type=0 -> all details
    //type=1 -> only groupID in 10,11,2
    //type=2 -> only event date and event type
    //type=4 -> only event type

    $sqlSettings = 'SELECT * FROM settings';
    $resultSettings = mysqli_query(db(), $sqlSettings) or exit(mysqli_error(db()));
    $rowSettings = mysqli_fetch_array($resultSettings, MYSQLI_ASSOC);
    $lang_locale = $rowSettings['lang_locale'];
    $time_format_normal = $rowSettings['time_format_normal'];
    setlocale(LC_TIME, $lang_locale);

    $sql = 'SELECT
						e.id,
						e.date AS `eventDate`,
						e.type AS `eventType`,
						et.description AS `eventTypeName`,
						g.id AS `groupId`,
						g.name AS `group`,
						r.name AS `role`,
						u.firstname,
						u.lastname
					FROM
						events e
						LEFT OUTER JOIN eventPeople ep ON e.id = ep.eventId
						LEFT OUTER JOIN userRoles ur ON ep.userRoleId = ur.id
						LEFT OUTER JOIN roles r ON ur.roleId = r.id
						LEFT OUTER JOIN groups g ON r.groupId = g.id
						LEFT OUTER JOIN users u ON ur.userId = u.id
						INNER JOIN eventTypes et ON e.type = et.id
					WHERE
						e.id = ? ';

    if ($type == 1) {
        $sql = $sql."AND ((g.id in (10,11)) OR (g.id=2 and u.firstname='Team')) ";
    }
    $sql = $sql.'ORDER BY e.id, g.id desc, role, firstname, lastname ';

    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $eventID);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);

    $returnValue = '';
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $id = $row['id'];
        $eventDate = $row['eventDate'];
        $eventType = $row['eventTypeName'];
        $role = $row['role'];

        $group = $row['group'];
        if ($apprev_description) {
            $group = substr($group, 0, 1);
        }

        $firstname = $row['firstname'];
        if ($firstname != 'Team') {
            $firstname = ltrim(substr($firstname, 0, 1).'.', '.');
        }

        $lastname = $row['lastname'];

        $returnValue = $returnValue.$separator;
        switch ($type) {
            case 0:
                //all persons of event
                //break;    ->  no special handling, fallthrough to case 1
            case 1:
                //only persons with groupID in 10,11,2  ->  handled in sql query
                $returnValue = $returnValue.$prefix.ltrim($group.': ');
                $returnValue = $returnValue.trim($firstname.' '.$lastname);
                break;
            case 2:
                //only event date and event type
                $returnValue = $returnValue.strftime($time_format_normal, strtotime($eventDate));
                $returnValue = $returnValue.$separator;
                $returnValue = $returnValue.$eventType;

                return trim(substr($returnValue, strlen($separator) - 1)); //ends while loop
                break;
            case 4:
                //only event type
                $returnValue = $returnValue.$eventType;

                return trim(substr($returnValue, strlen($separator) - 1)); //ends while loop
                break;
            case 8:
                break;
        }
    }
    mysqli_stmt_close($stmt);

    //return trim(substr($returnValue,strlen($separator)-1));
    return substr($returnValue, strlen($separator));
}
