<?php

namespace TechWilk\Rota;

function createCalendarToken($userId, $format, $description)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $token = RandomPassword(30, true, true, true);

    $sql = "INSERT INTO calendarTokens (userId, format, token, description) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'isss', $userId, $format, $token, $description);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return $token;
    } else {
        die(mysqli_error(db()));
    }
}

function checkCalendarToken($userId, $format, $token)
{
    $userId = filter_var($_GET['user'], FILTER_VALIDATE_INT);
    $sql = "SELECT COUNT(*) AS count FROM calendarTokens WHERE userId = ? AND format = ? AND token = ? AND revoked = false";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'iss', $userId, $format, $token);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    return $ob->count == 1;
}

function revokeCalendarToken($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $sql = "UPDATE calendarTokens SET revoked = true WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        return false;
    }
}

function calendarTokensForUser($userId)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT id, format, description, created, revoked FROM calendarTokens WHERE userId = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $calendars = [];
    while ($ob = mysqli_fetch_object($result)) {
        $calendars[] = $ob;
    }
    mysqli_stmt_close($stmt);

    return $calendars;
}
