<?php

namespace TechWilk\Rota;

session_start();

include_once 'includes/config.php';
include_once 'includes/functions.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['userid'];

$user = UserQuery::create()->findPK($userId);

// only load page if functionality enabled
if (siteConfig()['recording']['type'] != 'locomotivecms') {
    echo '<p>This feature is not enabled</p>';
    echo '<p><a href='.siteSettings()->getSiteUrl().'>&lt; back to homepage</a></p>';
    exit;
}

use GuzzleHttp\Client;

$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => siteConfig()['recording']['locomotivecms']['url'].'/locomotive/api/',
    // You can set any number of default request options.
    'timeout' => 2.0,
]);

// get token
$response = $client->request(
  'POST',
  'v3/tokens.json',
  ['form_params' => ['api_key' => siteConfig()['recording']['locomotivecms']['apiKey'],
                       'email' => siteConfig()['recording']['locomotivecms']['email'], ]]
  );
if ($response->getStatusCode() == 201) {
    $token = json_decode($response->getBody())->token;
}

// test token
if (isset($token)) {
    $response = $client->request(
  'GET',
  'v3/my_account.json',
  ['query' => ['auth_token' => $token]]);
    var_dump($response);
} else {
    echo '<p>Unable to connect to main site</p>';
    echo '<p><a href='.siteSettings()->getSiteUrl().'>&lt; back to homepage</a></p>';
    exit;
}
