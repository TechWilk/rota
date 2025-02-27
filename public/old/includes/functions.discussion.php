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

function cleanUpTextarea($discussion)
{
    $discussion = mysqli_real_escape_string(db(), $discussion);
    $discussion = str_replace("\r", '<br />', $discussion); // Keep new lines as . . . well, new lines
    $discussion = youtubeReplace($discussion);

    return $discussion;
}

function formatInput($string)
{
    $string = str_replace('[i]', '<em>', $string);
    $string = str_replace('[/i]', '</em>', $string);
    $string = str_replace('[b]', '<strong>', $string);
    $string = str_replace('[/b]', '</strong>', $string);

    return $string;
}

function youtubeReplace($string)
{
    return preg_replace(
        '#(http://(www.)?youtube.com)?/(v/|watch\?v\=)([-|~_0-9A-Za-z]+)&?.*?#i',
        '<iframe title="YouTube Video" width="560" height="340" src="http://www.youtube.com/embed/$4" frameborder="0" allowfullscreen></iframe>',
        $string
    );

    return $string;
}
