<?php
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
    $name = mysqli_real_escape_string(db(), $name);
    $description = mysqli_real_escape_string(db(), $description);

    $sql = "UPDATE cr_roles SET name = '$name', description = '$description' WHERE id = '$id'";

    if (!mysqli_query(db(), $sql)) {
        die('Error: ' . mysqli_error(db()));
    }
}



function updateGroup($key, $name, $description)
{
    $sql = "UPDATE cr_groups SET name = '$name' WHERE groupId = '$key'";

    if (!mysqli_query(db(), $sql)) {
        die('Error: ' . mysqli_error(db()));
    }

    $sql = "UPDATE cr_groups SET description = '$description' WHERE groupId = '$key'";

    if (!mysqli_query(db(), $sql)) {
        die('Error: ' . mysqli_error(db()));
    }
}



function moveRoleGroups($roleID, $value)
{
    $sql = "UPDATE cr_roles SET groupId = '$value' WHERE id = '$roleID'";

    if (!mysqli_query(db(), $sql)) {
        die('Error: ' . mysqli_error(db()));
    }
}



function addUserRole($userId, $roleId)
{
    $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
    $roleId = filter_var($roleId, FILTER_SANITIZE_NUMBER_INT);

    // prevent duplicate roles
    $sql = "SELECT COUNT(*) AS count FROM cr_userRoles WHERE roleId = '$roleId' AND userId = '$userId'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    if ($ob->count < 1) {
        $query = "INSERT INTO cr_userRoles (userId, roleId) VALUES ('$userId', '$roleId')";
        mysqli_query(db(), $query) or die(mysqli_error(db()));
    }
}



function removeUserRoleWithId($userRoleId)
{
    $query = "DELETE FROM cr_userRoles WHERE id = '$userRoleId'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
}



function removeUserRole($userId, $roleId)
{
    $query = "DELETE FROM cr_userRoles WHERE userId = '$userId' AND roleId = '$roleId'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
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
    $sql = "SELECT groupId FROM cr_roles WHERE id = '$roleId'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    
    return $ob->groupId;
}



function roleNameFromId($roleId)
{
    $sql = "SELECT name FROM cr_roles WHERE id = '$roleId'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    
    return $ob->name;
}



function roleCanSwapToOtherRoleInGroup($roleId)
{
    $sql = "SELECT allowRoleSwaps, groupId FROM cr_roles WHERE id = '$roleId'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    
    if ($ob->allowRoleSwaps != null) {
        return $ob->allowRoleSwaps;
    }
    
    $sql = "SELECT allowRoleSwaps FROM cr_groups WHERE id = '" . $ob->groupId . "'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    $ob = mysqli_fetch_object($result);
    
    return $ob->allowRoleSwaps;
}





if (!function_exists('array_combine')) {
    function array_combine($arr1, $arr2)
    {
        $out = array();
        foreach ($arr1 as $key1 => $value1) {
            $out[$value1] = $arr2[$key1];
        }
        return $out;
    }
}
