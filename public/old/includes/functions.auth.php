<?php

namespace TechWilk\Rota;

function setSessionAndRedirect($username)
{
    if (siteSettings()->getUsersStartWithMyEvents() == 1) {
        $users_start_with_myevents = '1';
    } else {
        $users_start_with_myevents = '0';
    }

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $_SESSION['db_is_logged_in'] = true;
        $_SESSION['isAdmin'] = $row['isAdmin']; // Set the admin status to be carried across this session
        $_SESSION['userid'] = $row['id'];
        $_SESSION['name'] = $row['firstName'].' '.$row['lastName'];
        $_SESSION['isBandAdmin'] = $row['isBandAdmin']; // Set the band admin status to be carried across this session
        $_SESSION['isEventEditor'] = $row['isEventEditor']; // Set the event editor status to be carried across this session
        $_SESSION['onlyShowUserEvents'] = $users_start_with_myevents; // 1 if users_start_with_myevents is set in settings, can be changed by user during session

        //statistic
        if (($debug) && (siteSettings()->getVersion() == '2.6.0')) {
            insertStatistics('user', __FILE__, 'login', null, $_SERVER['HTTP_USER_AGENT']);
        }

        // admin section
        if ($_SESSION['isAdmin'] == 1) {
            updateDatabase();                        //check for db updates
            //$_SESSION['onlyShowUserEvents'] = '0';		//show all events for admin, regardless what settings say
        }

        // Update last login timestamp
        $currentTimestamp = date('Y-m-d H:i:s');
        $sql = "UPDATE users SET lastLogin = '$currentTimestamp' WHERE id = '".$row['id']."'";
        mysqli_query(db(), $sql) or die(mysqli_error(db()));

        // redirect
        $redirectUrl = 'index.php';
        if (isset($_SESSION['redirectUrl'])) {
            $redirectFromSession = strip_tags($_SESSION['redirectUrl']);
            unset($_SESSION['redirectUrl']);
            // check is url is on same domain and prevents redirecting to logout page
            if (strncmp(strtolower(siteSettings()->getSiteUrl().'/'), strtolower($redirectFromSession), (strlen(siteSettings()->getSiteUrl()) + 1)) == 0 && strpos($redirectFromSession, 'logout.php') === false) {
                $redirectUrl = $redirectFromSession;
            }
        }
        header('Location: '.$redirectUrl);
        exit;
    }
}
