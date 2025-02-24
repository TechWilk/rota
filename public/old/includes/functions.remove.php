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

function removeSkill($skillID)
{
    $sql = "DELETE FROM skills WHERE skillID = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $skillID);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function removePost($postid)
{
    $sql = "DELETE FROM discussion WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $postid);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function removeCategory($areaid)
{
    $sql = "DELETE FROM discussionCategories WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $areaid);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
    header('Location: discussion.php');
}

function removeDiscussion($areaid)
{
    $sql = "DELETE FROM discussion WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $areaid);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);

    $sql = "DELETE FROM discussion WHERE topicParent = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $areaid);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
    header('Location: discussion.php');
}

function removeeventtype($eventtypeid)
{
    $sql = "DELETE FROM eventTypes WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $eventtypeid);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
    header('Location: editeventtype.php');
}

function removelocation($location)
{
    $sql = "DELETE FROM locations WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $location);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
    header('Location: locations.php');
}

function removeGroup($groupId)
{
    $sql = "DELETE FROM groups WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $groupId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);

    $sql = "DELETE FROM roles WHERE groupId = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $groupId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
    header('Location: roles.php');
}

function removeRole($roleId)
{
    $sql = "DELETE FROM roles WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $roleId);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
    header('Location: roles.php');
}

function removeBandMembers($bandMembersID)
{
    $sql = "DELETE FROM bandMembers WHERE bandMembersID = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $bandMembersID);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function removeResource($id)
{
    $sql = "DELETE FROM documents WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function removeEvent($removeWholeEvent)
{
    $sql = "UPDATE events SET removed = 1 WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $removeWholeEvent);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function removeBand($removeBand)
{
    $sql = "DELETE FROM bands WHERE bandID = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $removeBand);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
}

function removeBandSkill($bandskillid)
{
    $sql = "DELETE FROM instruments WHERE id = ?";
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 'i', $bandskillid);
    mysqli_stmt_execute($stmt) or die(mysqli_error(db()));
    mysqli_stmt_close($stmt);
    header('Location: bandskills.php');
}
