<?php

namespace TechWilk\Rota;

use Facebook;

include 'includes/config.php';
include 'includes/functions.php';

if ($config['auth']['facebook']['enabled'] != true) {
    header('Location: index.php');
    exit;
}

session_start();

$_SESSION['foo'] = 'bar';

$fb = new Facebook\Facebook([
  'app_id'                => $config['auth']['facebook']['appId'],
  'app_secret'            => $config['auth']['facebook']['appSecret'],
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl(siteSettings()->getSiteUrl().'/fb-callback.php', $permissions);

header('Location: '.$loginUrl);
