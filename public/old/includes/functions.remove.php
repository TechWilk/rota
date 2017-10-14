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
    $query = "DELETE FROM cr_skills WHERE skillID = '$skillID'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
}

function removePost($postid)
{
    $query = "DELETE FROM cr_discussion WHERE id = '$postid'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
}

function removeCategory($areaid)
{
    $query = "DELETE FROM cr_discussionCategories WHERE id = '$areaid'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    header('Location: discussion.php');
}

function removeDiscussion($areaid)
{
    $query = "DELETE FROM cr_discussion WHERE id = '$areaid'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    $query = "DELETE FROM cr_discussion WHERE topicParent = '$areaid'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    header('Location: discussion.php');
}

function removeeventtype($eventtypeid)
{
    $query = "DELETE FROM cr_eventTypes WHERE id = '$eventtypeid'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    header('Location: editeventtype.php');
}

function removelocation($location)
{
    $query = "DELETE FROM cr_locations WHERE id = '$location'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    header('Location: locations.php');
}

function removeGroup($groupId)
{
    $query = "DELETE FROM cr_groups WHERE id = '$groupId'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));

    $query = "DELETE FROM cr_roles WHERE groupId = '$groupId'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    header('Location: roles.php');
}

function removeRole($roleId)
{
    $query = "DELETE FROM cr_roles WHERE id = '$roleId'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    header('Location: roles.php');
}

function removeBandMembers($bandMembersID)
{
    $query = "DELETE FROM cr_bandMembers WHERE bandMembersID = '$bandMembersID'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
}

function removeResource($id)
{
    $query = "DELETE FROM cr_documents WHERE id = '$id'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
}

function removeEvent($removeWholeEvent)
{
    $query = "UPDATE cr_events SET removed = 1 WHERE id = '$removeWholeEvent'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
}

function removeBand($removeBand)
{
    $query = "DELETE FROM cr_bands WHERE bandID = '$removeBand'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
}

function removeBandSkill($bandskillid)
{
    $query = "DELETE FROM cr_instruments WHERE id = '$bandskillid'";
    mysqli_query(db(), $query) or die(mysqli_error(db()));
    header('Location: bandskills.php');
}
