<?php



//exit;

/*

require_once 'Database.php';
require_once 'Series.php';
include_once '../config/database.php';

try
{
  $series = new Series;

  $series->name = "Sample Sermon Series";
  $series->description = "Something about the second sample sermon series.";

  $db = new Database($config['db']);

  if($series->createInDb($db))
  {
    echo "Successfully added";
  }

}
catch(PDOException $e)
{
  echo "Error: " . $e->getMessage();
}
*/

// -----
// Interface with API
// -----

$id = 56;

$id = urlencode($id);

$url = "http://localhost/api/v1/series/" . $id;
$response = file_get_contents($url);

echo "URL: " . $url;

$responseData = json_decode($response, true);
$data = $responseData["data"];

echo var_dump($responseData);


echo "series " . $id . ": id = " . $data["id"] . ", name = " . $data["name"] . ", description = " . $data["description"];
