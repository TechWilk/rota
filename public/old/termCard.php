<?php namespace TechWilk\Rota;

// Include files, including the database connection
include 'includes/config.php';
include 'includes/functions.php';

// Start the session. This checks whether someone is logged in and if not redirects them
session_start();

// ensure user is logged in
if (!(isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true)) {
    $_SESSION['redirectUrl'] = siteSettings()->getSiteUrl().'/termCard.php';
    header('Location: login.php');
}

$sql = 'SELECT
          e.id AS id,
          e.name AS eventName,
          e.sermonTitle,
          e.bibleVerse,
          et.name AS eventType,
          est.name AS eventSubType,
          e.date AS eventDate
        FROM cr_events e
          LEFT JOIN cr_eventTypes et ON e.type = et.id
          LEFT JOIN cr_eventSubTypes est ON e.subType = est.id
          LEFT JOIN cr_locations l ON e.location = l.id
        WHERE e.date >= DATE(NOW())
          AND e.removed = 0
        ORDER BY eventDate';

$result = mysqli_query(db(), $sql) or die(mysqli_error(db()));

echo '<table>';
echo '<tr>';
echo '<th>Date</th>';
echo '<th>Service</th>';
echo '<th>Type</th>';
echo '<th>Type</th>';
echo '<th>Sermon</th>';
echo '</tr>';

while ($ob = mysqli_fetch_object($result)) {
    echo '<tr>';
    echo '<td>'.date('j F', strtotime($ob->eventDate)).'</td>';
    echo '<td>'.date('g:i a', strtotime($ob->eventDate)).'</td>';
    echo '<td>'.$ob->eventName.'</td>';
    echo '<td>'.$ob->eventSubType.'</td>';
    echo '<td>'.$ob->sermonTitle;
    echo $ob->bibleVerse ? ' ('.$ob->bibleVerse.')</td>' : '';
    echo '</tr>';
}
echo '</table>';

?>
<style>
table {
    border-collapse: collapse;
}
table, th, td {
   border: 1px solid black;
   padding: 5px;
}
</style>