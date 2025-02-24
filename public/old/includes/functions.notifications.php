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

    $sql = 'UPDATE notifications SET seen = TRUE WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $notificationId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);

    $sql = 'INSERT INTO notificationClicks (notificationId, referer) VALUES (?, ?)';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'is', $notificationId, $referer);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);

    return true;
}

function dismissNotification($notificationId)
{
    $notificationId = filter_var($notificationId, FILTER_SANITIZE_NUMBER_INT);

    $sql = 'UPDATE notifications SET dismissed = TRUE WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $notificationId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    mysqli_stmt_close($stmt);

    return true;
}

function notificationLink($notificationId)
{
    $notificationId = filter_var($notificationId, FILTER_SANITIZE_NUMBER_INT);

    $sql = 'SELECT link FROM notifications WHERE id = ?';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $notificationId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    return $ob->link;
}

function notificationWithId($notificationId)
{
    $notificationId = filter_var($notificationId, FILTER_SANITIZE_NUMBER_INT);

    $sql = 'SELECT id, summary, body, link, type, seen, dismissed FROM notifications WHERE id = ? LIMIT 1';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $notificationId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);
    $ob->type = notificationType($ob->type);

    return $ob;
}

function notificationWithIdHasType($notificationId, $type)
{
    $sql = 'SELECT type FROM notifications WHERE id = ? LIMIT 1';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $notificationId);
    mysqli_stmt_execute($stmt) or exit(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    return swapTypesArray()[$ob->type] == $type;
}

function notificationType($typeId)
{
    return swapTypesArray()[$typeId];
}
