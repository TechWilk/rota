<?php

namespace TechWilk\Rota;

/*
    This file is part of Church Rota.

    Copyright (C) 2015 Christopher Wilkinson

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

function updateRole($id, $name, $description)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $sql = "UPDATE roles SET name = ?, description = ? WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $description, $id);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function updateGroup($key, $name, $description)
{
    $sql = "UPDATE groups SET name = ?, description = ? WHERE groupId = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $description, $key);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function moveRoleGroups($roleID, $value)
{
    $sql = "UPDATE roles SET groupId = ? WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $value, $roleID);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function addUserRole($userId, $roleId)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $roleId = filter_var($roleId, FILTER_SANITIZE_NUMBER_INT);

    // prevent duplicate roles
    $sql = "SELECT COUNT(*) AS count FROM userRoles WHERE roleId = ? AND userId = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $roleId, $userId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    if ($ob->count < 1) {
        $sql = "INSERT INTO userRoles (userId, roleId) VALUES (?, ?)";
        $stmt = mysqli_prepare(db(), $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $userId, $roleId);
        mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
        mysqli_stmt_close($stmt);
    }
}

function removeUserRoleWithId($userRoleId)
{
    $sql = "DELETE FROM userRoles WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $userRoleId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function removeUserRole($userId, $roleId)
{
    $sql = "DELETE FROM userRoles WHERE userId = ? AND roleId = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $roleId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function setUserRoleReserveWithId($userRoleId)
{
    $userRole = UserRoleQuery::create()->findPk($userRoleId);
    $userRole->setReserve(true);
    $userRole->save();

    return true;
}

function setUserRoleRegularWithId($userRoleId)
{
    $userRole = UserRoleQuery::create()->findPk($userRoleId);
    $userRole->setReserve(false);
    $userRole->save();

    return true;
}

function groupIdWithRole($roleId)
{
    $sql = "SELECT groupId FROM roles WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $roleId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);
    return $ob->groupId;
}

function roleNameFromId($roleId)
{
    $sql = "SELECT name FROM roles WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $roleId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);
    return $ob->name;
}

function roleCanSwapToOtherRoleInGroup($roleId)
{
    $sql = "SELECT allowRoleSwaps, groupId FROM roles WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $roleId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    if ($ob->allowRoleSwaps != null) {
        return $ob->allowRoleSwaps;
    }

    $sql = "SELECT allowRoleSwaps FROM groups WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $ob->groupId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    $result = mysqli_stmt_get_result($stmt);
    $ob = mysqli_fetch_object($result);
    mysqli_stmt_close($stmt);

    return $ob->allowRoleSwaps;
}

if (!function_exists('array_combine')) {
    function array_combine($arr1, $arr2)
    {
        $out = [];
        foreach ($arr1 as $key1 => $value1) {
            $out[$value1] = $arr2[$key1];
        }

        return $out;
    }
}
