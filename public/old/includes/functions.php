<?php

namespace TechWilk\Rota;

use DateTime;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

include __DIR__.'/errors.php';

// setup the autoloading
require_once __DIR__.'/../../../vendor/autoload.php';

// setup Propel
require_once __DIR__.'/../../../generated-conf/config.php';

$defaultLogger = new Logger('defaultLogger');
$defaultLogger->pushHandler(new StreamHandler(__DIR__.'/../../../logs/propel.log', Logger::WARNING));

$serviceContainer->setLogger('defaultLogger', $defaultLogger);

// ~~~~~~ END OF ORM SETUP ~~~~~~

session_start();

// AUTH
if (isset($_SESSION['userId'])) {
    $_SESSION['userid'] = $_SESSION['userId'];
    $_SESSION['is_logged_in'] = true;
    $_SESSION['db_is_logged_in'] = true;
    $user = UserQuery::create()->findPk($_SESSION['userId']);
    $_SESSION['name'] = $user->getName();
    $_SESSION['isAdmin'] = $user->isAdmin() ? '1' : null;
} else {
    unset($_SESSION['userid']);
    unset($_SESSION['is_logged_in']);
    unset($_SESSION['db_is_logged_in']);
    unset($_SESSION['name']);
}
// END AUTH

date_default_timezone_set('UTC');

function utf8_wrapper($txt)
{
    if (!ini_get('default_charset') == 'utf-8') {
        return utf8_encode($txt);
    } else {
        return $txt;
    }
}

function getQueryStringForKey($key)
{
    if (isset($_GET[$key])) {
        return $_GET[$key];
    } else {
        return;
    }
}

function siteSettings()
{
    return SettingsQuery::create()->findOne();
}

include __DIR__.'/functions.auth.php';
include __DIR__.'/functions.notifications.php';
include __DIR__.'/functions.mail.php';
include __DIR__.'/functions.remove.php';
include __DIR__.'/functions.discussion.php';
include __DIR__.'/functions.event.php';
include __DIR__.'/functions.password.php';
include __DIR__.'/functions.users.php';
include __DIR__.'/functions.roles.php';
include __DIR__.'/functions.database.php';
include __DIR__.'/functions.calendars.php';
include __DIR__.'/functions.facebook.php';

date_default_timezone_set(siteSettings()->getTimeZone());

if ((isset($holdQuery)) && ($holdQuery == true)) {
    //set variables during installtion to default values
    $owner = 'A Church';
    $owneremail = '-';
    $version = '0.0.0';
    $debug = 0;
} else {
    //if call is not during installation,
    //query real values from db for these variables
    $sql = 'SELECT * FROM settings';
    $result = mysqli_query(db(), $sql) or exit(mysqli_error(db()));

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $owner = $row['owner'];
        $owneremail = $row['adminemailaddress'];
        $version = $row['version'];
        $debug = $row['debug_mode'];
    }
}

function isAdmin($userId = null)
{
    if (is_null($userId)) {
        if ($_SESSION['isAdmin'] == '1') {
            return true;
        } else {
            return false;
        }
    } else {
        $user = UserQuery::create()->findPk($userId);
        if ($user->getIsAdmin() == true) {
            return true;
        } else {
            return false;
        }
    }
}

function isBandAdmin($userId = null)
{
    if (is_null($userId)) {
        $userId = $_SESSION['userid'];
    }

    $user = UserQuery::create()->findPk($userId);
    if ($user->getIsBandAdmin() == true) {
        return true;
    } else {
        return false;
    }
}

function isEventEditor($userid = null)
{
    if (is_null($userId)) {
        $userId = $_SESSION['userid'];
    }

    $user = UserQuery::create()->findPk($userId);
    if ($user->getIsEventEditor() == true) {
        return true;
    } else {
        return false;
    }
}

function isLoggedIn()
{
    if ($_SESSION['db_is_logged_in'] == true) {
        return true;
    } else {
        return false;
    }
}

function subscribeto($userid, $categoryid, $topicid)
{
    $sql = 'INSERT INTO subscriptions(userid, categoryid, topicid) VALUES (?, ?, ?)';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $userid, $categoryid, $topicid);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function unsubscribefrom($subscription)
{
    $sql = 'DELETE FROM subscriptions WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $subscription);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function insertStatistics($type, $script, $detail1 = '', $detail2 = '', $detail3 = '')
{
    //if type=logout, then update login record (session-statitic-id) and exit
    //fallthrough if no login_statistic_id in session
    if (strtolower($detail1) == 'logout') {
        $stat_id = $_SESSION['login_statistic_id'];
        if (($stat_id != '') && ($stat_id != '0')) {
            $stat = StatisticQuery::create()->findPk($stat_id);
            $stat->setDetail1($stat->getDetail1().'/'.$detail1);
            $stat->setDetail2($stat->getDate()->diff(new DateTime()));
            $stat->save();

            return;
        }
    }

    $stat = new Statistic();
    if (isset($_SESSION['userid'])) {
        $stat->setUserId($_SESSION['userid']);
    }
    $stat->setDate(new DateTime());
    $stat->setType($type);
    $stat->setScript($script);
    $stat->setDetail1($detail1);
    $stat->setDetail2($detail2);
    $stat->setDetail3($detail3);
    $stat->save();

    //save auto-increment-id as session-statistic-id, when type=login
    if (strtolower($detail1) == 'login') {
        //get inserted auto-increment-id
        $_SESSION['login_statistic_id'] = $stat->getId();
    }
}

function timeAgoInWords($time)
{
    if (is_null($time)) {
        return 'never';
    }

    return timeInWords($time).' ago';
}

function timeInWordsWithTense($time)
{
    if (is_null($time)) {
        return 'never';
    }

    if (!is_a($time, 'DateTime')) {
        $time = new DateTime($time);
    }

    if ($time->getTimestamp() > (new DateTime())->getTimestamp()) {
        return 'in '.timeInWords($time);
    } else {
        return timeInWords($time).' ago';
    }
}

function timeInWords($time)
{
    if (is_null($time)) {
        return 'never';
    }

    if (!is_a($time, 'DateTime')) {
        $time = new DateTime($time);
    }

    $periods = ['second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade'];
    $lengths = ['60', '60', '24', '7', '4.35', '12', '10'];

    $now = new DateTime();

    $difference = abs($now->getTimestamp() - $time->getTimestamp());

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        $periods[$j] .= 's';
    }

    return "$difference $periods[$j]";
}
