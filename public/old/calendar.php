<?php

namespace TechWilk\Rota;

// Include files, including the database connection
include 'includes/config.php';
include 'includes/functions.php';

// get user ID and verify token
$userId = filter_var($_GET['user'], FILTER_VALIDATE_INT);
$token = $_GET['token'];
$format = $_GET['format'];

if (!checkCalendarToken($userId, $format, $token)) {
    http_response_code(401);
    echo '<h1>401 Unauthorised: User or token is incorrect</h1>';
    echo "<p>Please return to the <a href='calendarTokens.php'>account dashboard</a> and recreate your calendar URL.</p>";
    exit;
}

$sql = "
SELECT
  e.id,
  e.name,
  e.date,
  e.updated,
  e.sermonTitle,
  e.bibleVerse,
  eg.name AS series,
  l.name AS locationName,
  l.address AS locationAddress,
  r.name AS role,
  t.name AS type
FROM
  cr_events e
  INNER JOIN cr_locations l ON l.id = e.location
  INNER JOIN cr_eventTypes t ON t.id = e.type
  INNER JOIN cr_eventGroups eg ON eg.id = e.eventGroup
  INNER JOIN cr_eventPeople ep ON ep.eventId = e.id
  INNER JOIN cr_userRoles ur ON ur.id = ep.userRoleId
  INNER JOIN cr_roles r ON r.id = ur.roleId
WHERE
  ur.userId = $userId
  AND e.removed = 0";

$result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
while ($row = mysqli_fetch_object($result)) {
    $events[] = $row;
}

// fetch events

switch ($_GET['format']) {
  case 'ical':
    icalOutput($events);
    break;
}

function icalOutput($events)
{
    // the iCal date format.
    define('DATE_ICAL', 'Ymd\THis');
    define('HOUR', 60 * 60);

    // max line length is 75 chars. New line is \r\n

    $output = 'BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0
PRODID:-//Church Rota//Church Rota//EN
X-WR-CALNAME:'.siteSettings()->getOwner()." Rota
CALSCALE:GREGORIAN
BEGIN:VTIMEZONE
TZID:Europe/London
TZURL:http://tzurl.org/zoneinfo-outlook/Europe/London
X-LIC-LOCATION:Europe/London
BEGIN:DAYLIGHT
TZOFFSETFROM:+0000
TZOFFSETTO:+0100
TZNAME:BST
DTSTART:19700329T010000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:+0100
TZOFFSETTO:+0000
TZNAME:GMT
DTSTART:19701025T020000
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
END:STANDARD
END:VTIMEZONE\r\n";

    // loop over events
    foreach ($events as $event):
    $output .=
'BEGIN:VEVENT
SUMMARY:'.$event->role.($event->name ? ' | '.$event->name.' ('.$event->type.')' : ' ('.$event->type.')').'
DESCRIPTION:'.ical_split('DESCRIPTION:', $event->series.':\n'.$event->sermonTitle.' ('.$event->bibleVerse.')\n\nIf you are unable to do '.$event->role.' at this event, please request a swap:\n'.siteSettings()->getSiteUrl().'/swap.php?event='.$event->id.'\n\nOnce the swap is accepted, this event will be removed from your calendar.\nPlease be aware that changes may take up to 24 hours or longer to be reflected. This is due to your calendar, not on the rota system.').'
UID:ROTA'.$event->id.'
ORGANIZER;CN="'.siteSettings()->getOwner().'":MAILTO:'.siteSettings()->getAdminEmailAddress().'
STATUS:'.'CONFIRMED'.'
DTSTART;TZID="Europe/London":'.date(DATE_ICAL, strtotime($event->date)).'
DTEND;TZID="Europe/London":'.date(DATE_ICAL, strtotime($event->date) + HOUR).'
LAST-MODIFIED:'.date(DATE_ICAL, strtotime($event->updated)).'Z
LOCATION:'.ical_split('LOCATION:', $event->locationName.', '.$event->locationAddress).'
URL:'.siteSettings()->getSiteUrl().'/event.php?id='.$event->id."
BEGIN:VALARM
ACTION:DISPLAY
DESCRIPTION:Reminder for Rota
TRIGGER:-P1D
END:VALARM
END:VEVENT\r\n";
    endforeach;

    // close calendar
    $output .= 'END:VCALENDAR';

    echo $output;
}

// Taken from https://gist.github.com/hugowetterberg/81747
// Splits text into 75-octet lines to conform with iCal spec. See: http://www.ietf.org/rfc/rfc2445.txt, section 4.1
function ical_split($preamble, $value)
{
    $value = trim($value);
    $value = strip_tags($value);
    $value = preg_replace('/\n+/', ' ', $value); // remove newlines
  $value = preg_replace('/\s{2,}/', ' ', $value); // remove whitespace
  $value = str_replace(',', '\,', $value); // escape commas
  $preamble_len = strlen($preamble);
    $lines = [];
    while (strlen($value) > (75 - $preamble_len)) {
        $space = (75 - $preamble_len);
        $mbcc = $space;
        while ($mbcc) {
            $line = mb_substr($value, 0, $mbcc);
            $oct = strlen($line);
            if ($oct > $space) {
                $mbcc -= $oct - $space;
            } else {
                $lines[] = $line;
                $preamble_len = 1; // Still take the tab into account
                $value = mb_substr($value, $mbcc);
                break;
            }
        }
    }
    if (!empty($value)) {
        $lines[] = $value;
    }

    return implode($lines, "\n ");
}
