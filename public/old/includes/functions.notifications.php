<?php

namespace TechWilk\Rota;

function swapTypesArray()
{
    return [
        0 => 'general',
        1 => 'admin',
        2 => 'feature',
        3 => 'event',
        4 => 'reminder',
        5 => 'account',
        6 => 'swap',
        7 => 'swap-pending',
        8 => 'swap-approved',
        9 => 'email',
    ];
}

function createNotificationForUser($userId, $summary, $body, $typeName, $link = null)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $summary = substr($summary, 0, 40);

    $notification = new Notification();
    $notification->setUserId($userId);
    $notification->setSummary($summary);
    $notification->setBody($body);

    if (!is_null($link)) {
        $notification->setLink($link);
    }

    $type = array_search($typeName, swapTypesArray());
    if (is_null($type)) {
        $type = 0;
    }
    $notification->setType($type);

    $notification->save();

    return $notification->getId();
}

function seenNotification($notificationId, $referer)
{
    $notificationId = filter_var($notificationId, FILTER_SANITIZE_NUMBER_INT);
    $referer = mysqli_real_escape_string(db(), $referer);

    $sql = "UPDATE notifications SET seen = TRUE WHERE id = $notificationId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    $sql = "INSERT INTO notificationClicks (notificationId, referer) VALUES ($notificationId, '$referer')";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    return true;
}

function dismissNotification($notificationId)
{
    $notificationId = filter_var($notificationId, FILTER_SANITIZE_NUMBER_INT);

    $sql = "UPDATE notifications SET dismissed = TRUE WHERE id = $notificationId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

    return true;
}

function notificationLink($notificationId)
{
    $notificationId = filter_var($notificationId, FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT link FROM notifications WHERE id = $notificationId";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);

    return $ob->link;
}

function notificationWithId($notificationId)
{
    $notificationId = filter_var($notificationId, FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT id, summary, body, link, type, seen, dismissed FROM notifications WHERE id = $notificationId LIMIT 1";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);

    $ob->type = notificationType($ob->type);

    return $ob;
}

function notificationWithIdHasType($notificationId, $type)
{
    $sql = "SELECT type FROM notifications WHERE id = $notificationId LIMIT 1";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);

    return swapTypesArray()[$ob->type] == $type;
}

function notificationType($typeId)
{
    return swapTypesArray()[$typeId];
}
