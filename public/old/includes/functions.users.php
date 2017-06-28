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

function addUser($firstName, $lastName, $email, $mobile)
{
    $firstName = trim(strip_tags($firstName));
    $firstNameLower = strtolower($firstName);
    $lastName = trim(strip_tags($lastName));
    $lastNameLower = strtolower($lastName);
    $email = mysqli_real_escape_string(db(), filter_var($email, FILTER_SANITIZE_EMAIL));
    $mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);

    if (empty($firstName) && empty($lastName)) {
        return;
    }

    // create username and remove all whitespace
    $username = $firstNameLower . "." . $lastNameLower;
    $username = preg_replace('/\s+/', '', $username);

    $sql = ("INSERT INTO cr_users (firstName, lastName, username, email, mobile, password, created, updated)
VALUES ('$firstName', '$lastName', '$username', '$email', '$mobile', '1', NOW(), NOW())");

    mysqli_query(db(), $sql) or die(mysqli_error(db()));

    $id = mysqli_insert_id(db());
    
    $notificationMessage = "Welcome to your new account on the rota system.\n
If you have any issues, please get in touch with us [".siteSettings()->getAdminEmailAddress()."](mailto:".siteSettings()->getAdminEmailAddress().").\n
---\n
**Sync to digital calendar**\n
You may wish to link the rota to your digital calendar on your computer and phone.  To do so, generate a [calendar token](calendarTokens.php) which will present you with your unique URL.  Follow instructions from your digital calendar provider for exact details on how import an iCal feed, or get in touch and we may be able to help.\n";
    
    createNotificationForUser($id, "Welcome ".$firstName, $notificationMessage, "feature");
    createNotificationForUser($id, "Change your password", "Please change your password to something unique and memorable.", "account", "editPassword.php");

    return $id;
}


function updateUser($id, $firstName, $lastName, $email, $mobile)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $firstName = trim(strip_tags($firstName));
    $lastName = trim(strip_tags($lastName));
    $email = mysqli_real_escape_string(db(), filter_var($email, FILTER_SANITIZE_EMAIL));
    $mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);

    $sql = "UPDATE cr_users SET firstname = '$firstName', lastname = '$lastName', email = '$email', mobile = '$mobile' WHERE id = '$id'";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));
}


function updateUserContactDetails($id, $email, $mobile)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $email = mysqli_real_escape_string(db(), filter_var($email, FILTER_SANITIZE_EMAIL));
    $mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);

    $sql = "UPDATE cr_users SET email = '$email', mobile = '$mobile' WHERE id = '$id'";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));
}


function removeUser($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $query = "DELETE FROM cr_userRoles WHERE userId = $id";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    $query = "DELETE FROM cr_socialAuth WHERE userId = $id";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    $query = "DELETE FROM cr_notifications WHERE userId = $id";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    $query = "DELETE FROM cr_users WHERE id = $id";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
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
    if ($isAdmin) {
        $isAdmin = '1';
    } else {
        $isAdmin = '0';
    }
    if ($isOverviewRecipient) {
        $isOverviewRecipient = '1';
    } else {
        $isOverviewRecipient = '0';
    }
    if ($isBandAdmin) {
        $isBandAdmin = '1';
    } else {
        $isBandAdmin = '0';
    }
    if ($isEventEditor) {
        $isEventEditor = '1';
    } else {
        $isEventEditor = '0';
    }
    $sql = "UPDATE cr_users
					SET isAdmin = '$isAdmin',
					isOverviewRecipient = '$isOverviewRecipient',
					isBandAdmin = '$isBandAdmin',
					isEventEditor = '$isEventEditor'
					WHERE id = '$id'";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));
}

function getNameWithId($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT firstName, lastName FROM cr_users WHERE id = '$id'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    $name = $ob->firstName . " " . $ob->lastName;
    return $name;
}

function getFirstNameWithId($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT firstName FROM cr_users WHERE id = '$id'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    return $ob->firstName;
}

function getUsernameWithId($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT username FROM cr_users WHERE id = '$id'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    while ($row =  mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $username = $row['username'];
    }
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
    $sql = "SELECT email FROM cr_users WHERE id = '$id'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    while ($row =  mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $email = $row['email'];
    }
    return $email;
}

function changePassword($userId, $plainTextNewPassword, $plainTextOldPassword)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    if (!isPasswordCorrectWithId($userId, $plainTextOldPassword)) {
        return false;
    }
    $hashedPassword = hashPassword($plainTextNewPassword);
    $currentTimestamp = date("Y-m-d H:i:s");
    $sql = "UPDATE cr_users SET password = '$hashedPassword', passwordChanged = '$currentTimestamp' WHERE id = '$userId'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    return isPasswordCorrectWithId($userId, $plainTextNewPassword);
}

function forceChangePassword($userId, $plainTextNewPassword)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $newPassword = hashPassword($plainTextNewPassword);
    $currentTimestamp = date("Y-m-d H:i:s");
    $sql = "UPDATE cr_users SET password = '$newPassword', passwordChanged = '$currentTimestamp' WHERE id = '$userId'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    insertStatistics("user", __FILE__, "password force changed for user ".getNameWithId($userId), null, $_SERVER['HTTP_USER_AGENT']);
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

    $username = mysqli_real_escape_string(db(), $username);
    $ipAddress = mysqli_real_escape_string(db(), $ipAddress);

    $loginFailures = LoginFailureQuery::create()->filterByUsername($username)->filterByTimestamp(['min' => date('Y-m-d H:i:s', strtotime("-$numberOfAllowedAttempts minutes"))])->count();

    if ($loginFailures < $numberOfAllowedAttempts) {
        return true;
    } else {
        insertStatistics("user", __FILE__, "Login attempts exceeded for username: ".$username, $ipAddress, $_SERVER['HTTP_USER_AGENT']);
        return false;
    }
}

function logFailedLoginAttempt($username, $ipAddress)
{
    $username = mysqli_real_escape_string(db(), $username);
    $ipAddress = mysqli_real_escape_string(db(), $ipAddress);
    $sql = "INSERT INTO cr_loginFailures (username, ipAddress) VALUES ('$username', '$ipAddress')";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));
}


function userIsAdmin($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT isAdmin FROM cr_users WHERE id = '$id'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    if ($ob->isAdmin) {
        return true;
    } else {
        return false;
    }
}


function userExistsWithSocialIdForPlatform($socialId, $platform)
{
    $socialId = filter_var($socialId, FILTER_SANITIZE_NUMBER_INT);
    $platform = mysqli_real_escape_string(db(), $platform);
    $sql = "SELECT COUNT(*) AS count FROM cr_socialAuth WHERE socialId = $socialId AND platform = '$platform'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    
    if ($ob->count == 1) {
        return true;
    } else {
        return false;
    }
}


function usersWhoAreAdmins()
{
    $sql = "SELECT id, firstName, lastName FROM cr_users WHERE isAdmin = true ORDER BY lastName, firstName";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    while ($ob = mysqli_fetch_object($result)) {
        $admins[] = $ob;
    }
    return $admins;
}

function allUsersNames()
{
    $sql = "SELECT id, firstName, lastName FROM cr_users ORDER BY lastName, firstName";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    while ($ob = mysqli_fetch_object($result)) {
        $users[] = $ob;
    }
    return $users;
}


function createPendingUser($socialId, $firstName, $lastName, $email, $source)
{
    $socialId = filter_var($socialId, FILTER_SANITIZE_NUMBER_INT);
    $firstName = mysqli_real_escape_string(db(), trim($firstName));
    $lastName = mysqli_real_escape_string(db(), trim($lastName));
    $email = mysqli_real_escape_string(db(), trim($email));
    $source = mysqli_real_escape_string(db(), $source);
    
    $sql = "INSERT INTO cr_pendingUsers (socialId, firstName, lastName, email, source) VALUES ($socialId, '$firstName', '$lastName', '$email', '$source')";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    
    $pendingId = mysqli_insert_id(db());
    $linkToApprove = "pendingAccounts.php?id=".$pendingId;
    
    $email = siteSettings()->getOwner().'<'.siteSettings()->getAdminEmailAddress().'>';
    $subject = $firstName.' requested an account';
    $message = $subject . " through ".$source.".\nApprove or decline: ".siteSettings()->getSiteUrl().'/'.$linkToApprove;

    sendMail($email, $subject, $message, $email);

    $admins = usersWhoAreAdmins();
    
    foreach ($admins as $user) {
        createNotificationForUser($user->id, $subject, $message, "account", $linkToApprove);
    }
}


function pendingUserActioned($pendingAccountId)
{
    $pendingAccountId = filter_var($pendingAccountId, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT approved, declined FROM cr_pendingUsers WHERE id = $pendingAccountId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    
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
    $sql = "UPDATE cr_pendingUsers SET approved = true WHERE id = $pendingAccountId";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));
    
    // create user
    $sql = "SELECT firstName, lastName, email, socialId, source FROM cr_pendingUsers WHERE id = $pendingAccountId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $pendingUser = mysqli_fetch_object($result);
    $userId = addUser($pendingUser->firstName, $pendingUser->lastName, $pendingUser->email, "");
    
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
    $sql = "UPDATE cr_pendingUsers SET approved = true WHERE id = $pendingAccountId";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));
    
    // add facebook login id
    $sql = "SELECT firstName, lastName, email, socialId, source FROM cr_pendingUsers WHERE id = $pendingAccountId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $pendingUser = mysqli_fetch_object($result);
    addSocialAuthToUserWithId($userId, $pendingUser->socialId, $pendingUser->source);
    updateUser($userId, $pendingUser->firstName, $pendingUser->lastName, $pendingUser->email, null);
    
    createNotificationForUser($userId, "Social Login added: ".$pendingUser->source, "Your social media login details for ".$pendingUser->source." have been added to your existing account", "account");
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
    $sql = "UPDATE cr_pendingUsers SET declined = true WHERE id = $pendingAccountId";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));

    $sql = "SELECT socialId FROM cr_pendingUsers WHERE id = $pendingAccountId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $pendingUser = mysqli_fetch_object($result);

    createFacebookNotificationForFacebookUser($pendingUser->socialId, 'login.php', 'Your account request has been declined. Get in touch if you think we\'ve got this wrong.');
    
    return true;
}


function addSocialAuthToUserWithId($userId, $socialId, $platform)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $socialId = filter_var($socialId, FILTER_SANITIZE_NUMBER_INT);
    $platform = mysqli_real_escape_string(db(), $platform);
    $sql = "INSERT INTO cr_socialAuth (userId, socialId, platform) VALUES ($userId, $socialId, '$platform')";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
}


function removeSocialAuthFromUserWithId($userId, $platform)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $platform = mysqli_real_escape_string(db(), $platform);
    $sql = "DELETE FROM cr_socialAuth WHERE userId = $userId AND platform = '$platform'";
    mysqli_query(db(), $sql) or die(mysqli_error(db()));
}


function getUsernameWithSocialId($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = "SELECT username FROM cr_users u INNER JOIN cr_socialAuth sa ON u.id = sa.userId WHERE sa.socialId = '$id'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob =  mysqli_fetch_object($result);
    return $ob->username;
}


function userIsLinkedToPlatform($userId, $platform)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $platform = mysqli_real_escape_string(db(), $platform);
    $sql = "SELECT COUNT(*) AS count FROM cr_socialAuth WHERE userId = $userId AND platform = '$platform' AND revoked = 0";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    
    if ($ob->count == 1) {
        return true;
    } else {
        return false;
    }
}

function userSocialIdForPlatform($userId, $platform)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $platform = mysqli_real_escape_string(db(), $platform);
    $sql = "SELECT socialId FROM cr_socialAuth WHERE userId = $userId AND platform = '$platform' AND revoked = 0";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    
    return $ob->socialId;
}

function getProfileImageUrl($userId, $size = 'small')
{
    $sql = "SELECT sa.socialId, u.email FROM cr_users u LEFT JOIN cr_socialAuth sa ON sa.userId = u.id WHERE id = ".$userId;
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $user = mysqli_fetch_object($result);

    if ($user->socialId) {
        switch ($size) {
            case 'small': // 50px x 50px
                return '//graph.facebook.com/' . $user->socialId . '/picture?type=square';
                break;
            case 'large': // 200px x 200px
                return '//graph.facebook.com/' . $user->socialId . '/picture?type=large';
                break;
            default:
                return '//graph.facebook.com/' . $user->socialId . '/picture';
                break;
        }
    } else {
        switch ($size) {
            case 'small': // 50px x 50px
                return '//www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=50&d=mm';
                break;
            case 'large': // 200px x 200px
                return '//www.gravatar.com/avatar/' . md5(strtolower(trim($user->email)))  . '?s=200&d=mm';
                break;
            default:
                return '//www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=50&d=mm';
                break;
        }
    }
}
