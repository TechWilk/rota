<?php

namespace TechWilk\Rota;

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

function addUser($firstName, $lastName, $email, $mobile)
{
    $firstName = trim(strip_tags($firstName));
    $firstNameLower = strtolower($firstName);
    $lastName = trim(strip_tags($lastName));
    $lastNameLower = strtolower($lastName);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);

    if (empty($firstName) && empty($lastName)) {
        return;
    }

    // create username and remove all whitespace
    $username = $firstNameLower.'.'.$lastNameLower;
    $username = preg_replace('/\s+/', '', $username);

    $sql = "INSERT INTO users (firstName, lastName, username, email, mobile, password, created, updated)
    VALUES (?, ?, ?, ?, ?, '1', NOW(), NOW())";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'sssss', $firstName, $lastName, $username, $email, $mobile);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);

    $id = mysqli_insert_id(db());

    $notificationMessage = "Welcome to your new account on the rota system.\n
If you have any issues, please get in touch with us [".siteSettings()->getAdminEmailAddress().'](mailto:'.siteSettings()->getAdminEmailAddress().").\n
---\n
**Sync to digital calendar**\n
You may wish to link the rota to your digital calendar on your computer and phone.  To do so, generate a [calendar token](calendarTokens.php) which will present you with your unique URL.  Follow instructions from your digital calendar provider for exact details on how import an iCal feed, or get in touch and we may be able to help.\n";

    createNotificationForUser($id, 'Welcome '.$firstName, $notificationMessage, 'feature');
    createNotificationForUser($id, 'Change your password', 'Please change your password to something unique and memorable.', 'account', 'editPassword.php');

    return $id;
}

function updateUser($id, $firstName, $lastName, $email, $mobile)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $firstName = trim(strip_tags($firstName));
    $lastName = trim(strip_tags($lastName));
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);

    $sql = 'UPDATE users SET firstname = ?, lastname = ?, email = ?, mobile = ? WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $firstName, $lastName, $email, $mobile, $id);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function updateUserContactDetails($id, $email, $mobile)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);

    $sql = 'UPDATE users SET email = ?, mobile = ? WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $email, $mobile, $id);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function removeUser($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $queries = [
        'DELETE FROM userRoles WHERE userId = ?',
        'DELETE FROM socialAuth WHERE userId = ?',
        'DELETE FROM notifications WHERE userId = ?',
        'DELETE FROM users WHERE id = ?',
    ];

    foreach ($queries as $sql) {
        $stmt = mysqli_prepare(db(), $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
        mysqli_stmt_close($stmt);
    }

    return 1;
}
/*
function userPermissions($id) {

    return 1;
}

function addPermission($id) {

}

function removePermission($id) {

}*/

function updatePermissions($id, $isAdmin, $isBandAdmin, $isEventEditor, $isOverviewRecipient)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $isAdmin = (bool) $isAdmin;
    $isOverviewRecipient = (bool) $isOverviewRecipient;
    $isBandAdmin = (bool) $isBandAdmin;
    $isEventEditor = (bool) $isEventEditor;

    $sql = 'UPDATE users
            SET isAdmin = ?,
            isOverviewRecipient = ?,
            isBandAdmin = ?,
            isEventEditor = ?
            WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'iiiii', $isAdmin, $isOverviewRecipient, $isBandAdmin, $isEventEditor, $id);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function getNameWithId($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT firstName, lastName FROM users WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);
    $name = $ob->firstName.' '.$ob->lastName;

    return $name;
}

function getFirstNameWithId($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT firstName FROM users WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    return $ob->firstName;
}

function getUsernameWithId($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT username FROM users WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $username = $row['username'];
    }
    mysqli_stmt_close($stmt);

    return $username;
}

function getIdWithUsername($username)
{
    try {
        $user = UserQuery::create()->filterByUsername($username)->findOne();
        if (is_a($user, 'User')) {
            return $user->getId();
        } else {
            return 0;
        }
    } catch (\Error $e) {
        return 0;
    }
}

function getEmailWithId($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT email FROM users WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $email = $row['email'];
    }
    mysqli_stmt_close($stmt);

    return $email;
}

function changePassword($userId, $plainTextNewPassword, $plainTextOldPassword)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    if (!isPasswordCorrectWithId($userId, $plainTextOldPassword)) {
        return false;
    }
    $hashedPassword = hashPassword($plainTextNewPassword);
    $currentTimestamp = date('Y-m-d H:i:s');
    $sql = 'UPDATE users SET password = ?, passwordChanged = ? WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $hashedPassword, $currentTimestamp, $userId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);

    return isPasswordCorrectWithId($userId, $plainTextNewPassword);
}

function forceChangePassword($userId, $plainTextNewPassword)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $hashedPassword = hashPassword($plainTextNewPassword);
    $currentTimestamp = date('Y-m-d H:i:s');
    $sql = 'UPDATE users SET password = ?, passwordChanged = ? WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $hashedPassword, $currentTimestamp, $userId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);
    insertStatistics('user', __FILE__, 'password force changed for user '.getNameWithId($userId), null, $_SERVER['HTTP_USER_AGENT']);
}

function hashPassword($plainTextPassword)
{
    $bcrypt_options = [
        'cost' => 12,
    ];

    return password_hash($plainTextPassword, PASSWORD_BCRYPT, $bcrypt_options);
}

function isPasswordCorrectWithId($userId, $plainTextPassword)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);

    try {
        $user = UserQuery::create()->findPK($userId);
        if (is_a($user, 'User')) {
            return $user->checkPassword($plainTextPassword);
        } else {
            return false;
        }
    } catch (\Error $e) {
        return false;
    }
}

function isPasswordCorrectWithUsername($username, $plainTextPassword)
{
    return isPasswordCorrectWithId(getIdWithUsername($username), $plainTextPassword);
}

function numberOfLoginAttemptsIsOk($username, $ipAddress)
{
    $numberOfAllowedAttempts = 8;
    $lockOutInterval = 15; // mins

    $loginFailures = LoginFailureQuery::create()
        ->filterByUsername($username)
        ->filterByTimestamp(['min' => date('Y-m-d H:i:s', strtotime("-$lockOutInterval minutes"))])
        ->count();

    if ($loginFailures < $numberOfAllowedAttempts) {
        return true;
    } else {
        insertStatistics('user', __FILE__, 'Login attempts exceeded for username: '.$username, $ipAddress, $_SERVER['HTTP_USER_AGENT']);

        return false;
    }
}

function logFailedLoginAttempt($username, $ipAddress)
{
    $sql = 'INSERT INTO loginFailures (username, ipAddress) VALUES (?, ?)';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $ipAddress);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function userIsAdmin($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT isAdmin FROM users WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);
    if ($ob->isAdmin) {
        return true;
    } else {
        return false;
    }
}

function userExistsWithSocialIdForPlatform($socialId, $platform)
{
    $socialId = filter_var($socialId, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT COUNT(*) AS count FROM socialAuth WHERE socialId = ? AND platform = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'is', $socialId, $platform);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    if ($ob->count == 1) {
        return true;
    } else {
        return false;
    }
}

function usersWhoAreAdmins()
{
    $sql = 'SELECT id, firstName, lastName FROM users WHERE isAdmin = true ORDER BY lastName, firstName';
    $result = mysqli_query(db(), $sql) or exit(mysqli_error(db()));
    $admins = [];
    while ($ob = mysqli_fetch_object($result)) {
        $admins[] = $ob;
    }

    return $admins;
}

function allUsersNames()
{
    $sql = 'SELECT id, firstName, lastName FROM users ORDER BY lastName, firstName';
    $result = mysqli_query(db(), $sql) or exit(mysqli_error(db()));
    $users = [];
    while ($ob = mysqli_fetch_object($result)) {
        $users[] = $ob;
    }

    return $users;
}

function createPendingUser($socialId, $firstName, $lastName, $email, $source)
{
    $socialId = filter_var($socialId, FILTER_SANITIZE_NUMBER_INT);
    $firstName = trim($firstName);
    $lastName = trim($lastName);
    $email = trim($email);

    $sql = 'INSERT INTO pendingUsers (socialId, firstName, lastName, email, source) VALUES (?, ?, ?, ?, ?)';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'issss', $socialId, $firstName, $lastName, $email, $source);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $pendingId = mysqli_insert_id(db());
    mysqli_stmt_close($stmt);

    $linkToApprove = 'pendingAccounts.php?id='.$pendingId;

    $email = siteSettings()->getOwner().'<'.siteSettings()->getAdminEmailAddress().'>';
    $subject = $firstName.' requested an account';
    $message = $subject.' through '.$source.".\nApprove or decline: ".siteSettings()->getSiteUrl().'/'.$linkToApprove;

    sendMail($email, $subject, $message, $email);

    $admins = usersWhoAreAdmins();

    foreach ($admins as $user) {
        createNotificationForUser($user->id, $subject, $message, 'account', $linkToApprove);
    }
}

function pendingUserActioned($pendingAccountId)
{
    $pendingAccountId = filter_var($pendingAccountId, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT approved, declined FROM pendingUsers WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $pendingAccountId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    if ($ob->approved == true || $ob->declined == true) {
        return true;
    } else {
        return false;
    }
}

function approvePendingUser($pendingAccountId)
{
    $pendingAccountId = filter_var($pendingAccountId, FILTER_SANITIZE_NUMBER_INT);

    // if not already approved or declined
    if (pendingUserActioned($pendingAccountId)) {
        return false;
    }

    // update approval bit
    $sql = 'UPDATE pendingUsers SET approved = true WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $pendingAccountId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);

    // create user
    $sql = 'SELECT firstName, lastName, email, socialId, source FROM pendingUsers WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $pendingAccountId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $pendingUser = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    $userId = addUser($pendingUser->firstName, $pendingUser->lastName, $pendingUser->email, '');

    // add facebook login id
    addSocialAuthToUserWithId($userId, $pendingUser->socialId, $pendingUser->source);
    createFacebookNotificationForUser($userId, 'login.php', 'Welcome to the rota! Your account request has been approved and you can now login via Facebook.');

    return $userId;
}

function mergePendingUserWithUserId($pendingAccountId, $userId)
{
    $pendingAccountId = filter_var($pendingAccountId, FILTER_SANITIZE_NUMBER_INT);
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);

    // if not already approved or declined
    if (pendingUserActioned($pendingAccountId)) {
        return false;
    }

    // update approval bit
    $sql = 'UPDATE pendingUsers SET approved = true WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $pendingAccountId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);

    // add facebook login id
    $sql = 'SELECT firstName, lastName, email, socialId, source FROM pendingUsers WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $pendingAccountId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $pendingUser = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    addSocialAuthToUserWithId($userId, $pendingUser->socialId, $pendingUser->source);
    updateUser($userId, $pendingUser->firstName, $pendingUser->lastName, $pendingUser->email, null);

    createNotificationForUser($userId, 'Social Login added: '.$pendingUser->source, 'Your social media login details for '.$pendingUser->source.' have been added to your existing account', 'account');
    createFacebookNotificationForUser($userId, 'login.php', 'Your account request has been approved. You can now login via Facebook.');

    return true;
}

function declinePendingUser($pendingAccountId)
{
    $pendingAccountId = filter_var($pendingAccountId, FILTER_SANITIZE_NUMBER_INT);

    // if not already approved or declined
    if (pendingUserActioned($pendingAccountId)) {
        return false;
    }

    // update declined bit
    $sql = 'UPDATE pendingUsers SET declined = true WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $pendingAccountId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);

    $sql = 'SELECT socialId FROM pendingUsers WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $pendingAccountId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $pendingUser = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    createFacebookNotificationForFacebookUser($pendingUser->socialId, 'login.php', 'Your account request has been declined. Get in touch if you think we\'ve got this wrong.');

    return true;
}

function addSocialAuthToUserWithId($userId, $socialId, $platform)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $socialId = filter_var($socialId, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'INSERT INTO socialAuth (userId, socialId, platform) VALUES (?, ?, ?)';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'iis', $userId, $socialId, $platform);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function removeSocialAuthFromUserWithId($userId, $platform)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'DELETE FROM socialAuth WHERE userId = ? AND platform = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'is', $userId, $platform);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function getUsernameWithSocialId($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT username FROM users u INNER JOIN socialAuth sa ON u.id = sa.userId WHERE sa.socialId = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    return $ob->username;
}

function userIsLinkedToPlatform($userId, $platform)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT COUNT(*) AS count FROM socialAuth WHERE userId = ? AND platform = ? AND revoked = 0';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'is', $userId, $platform);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    if ($ob->count == 1) {
        return true;
    } else {
        return false;
    }
}

function userSocialIdForPlatform($userId, $platform)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT socialId FROM socialAuth WHERE userId = ? AND platform = ? AND revoked = 0';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'is', $userId, $platform);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    return $ob->socialId;
}

function getProfileImageUrl($userId, $size = 'small')
{
    $sql = 'SELECT sa.socialId, u.email FROM users u LEFT JOIN socialAuth sa ON sa.userId = u.id WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    if ($user->socialId) {
        switch ($size) {
            case 'small': // 50px x 50px
                return '//graph.facebook.com/'.$user->socialId.'/picture?type=square';
                break;
            case 'large': // 200px x 200px
                return '//graph.facebook.com/'.$user->socialId.'/picture?type=large';
                break;
            default:
                return '//graph.facebook.com/'.$user->socialId.'/picture';
                break;
        }
    } else {
        switch ($size) {
            case 'small': // 50px x 50px
                return '//www.gravatar.com/avatar/'.md5(strtolower(trim($user->email))).'?s=50&d=mm';
                break;
            case 'large': // 200px x 200px
                return '//www.gravatar.com/avatar/'.md5(strtolower(trim($user->email))).'?s=200&d=mm';
                break;
            default:
                return '//www.gravatar.com/avatar/'.md5(strtolower(trim($user->email))).'?s=50&d=mm';
                break;
        }
    }
}
