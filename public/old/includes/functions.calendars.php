<?php

namespace TechWilk\Rota;

function createCalendarToken($userId, $format, $description)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $format = mysqli_real_escape_string(db(), $format);
    $description = mysqli_real_escape_string(db(), $description);

    $token = mysqli_real_escape_string(db(), RandomPassword(30, true, true, true));

    $sql = "INSERT INTO calendarTokens (userId, format, token, description) VALUES ($userId, '$format', '$token', '$description')";
    if (mysqli_query(db(), $sql)) {
        return $token;
    } else {
        exit(mysqli_error(db()));

        return;
    }
}

function checkCalendarToken($userId, $format, $token)
{
    $userId = filter_var($_GET['user'], FILTER_VALIDATE_INT);
    $format = mysqli_real_escape_string(db(), $format);
    $token = mysqli_real_escape_string(db(), $token);

    $sql = "SELECT COUNT(*) AS count FROM calendarTokens WHERE userId = $userId AND format = '$format' AND token = '$token' AND revoked = false";
    $result = mysqli_query(db(), $sql) or exit(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);

    if ($ob->count == 1) {
        return true;
    } else {
        return false;
    }
}

function revokeCalendarToken($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $sql = "UPDATE calendarTokens SET revoked = true WHERE id = $id";
    if (mysqli_query(db(), $sql)) {
        return true;
    } else {
        return false;
    }
}

function calendarTokensForUser($userId)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT id, format, description, created, revoked FROM calendarTokens WHERE userId = $userId";
    $result = mysqli_query(db(), $sql) or exit(mysqli_error(db()));
    while ($ob = mysqli_fetch_object($result)) {
        $calendars[] = $ob;
    }

    return $calendars;
}
