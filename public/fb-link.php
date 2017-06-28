<?php
    ini_set('display_errors', false);    // set on for development, off for production
    ini_set('log_errors', true);
    error_reporting(E_ALL);
  
  date_default_timezone_set("Europe/London");

// Include files, including the database connection
include('includes/config.php');
include('includes/functions.php');

if ($config['auth']['facebook']['enabled'] != true) {
    header('Location: index.php');
    exit;
}

if (!session_id()) {
    session_start();
}

require_once 'vendor/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => $config['auth']['facebook']['appId'],
  'app_secret' => $config['auth']['facebook']['appSecret'],
  'default_graph_version' => 'v2.2',
  ]);
  
$accessToken = $_SESSION['fb_access_token'];

// fetch user info

try {
    // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,email', $accessToken);
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user = $response->getGraphUser();

addSocialAuthToUserWithId($_SESSION['userid'], $user->getId(), 'facebook');
createNotificationForUser($_SESSION['userid'], "Facebook Login enabled", "You have successfully linked your Facebook account.  Login via Facebook is now enabled for your account.", "account");
header('Location: linkSocialAuth.php');
