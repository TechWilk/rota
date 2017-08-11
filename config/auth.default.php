<?php

// choose either 'database', 'onebody' or 'facebook'
// onebody and facebook require additional config (below)
$config['auth']['scheme'] = 'database';

// Facebook login requires and App Id and Secret generated through the Facebook's App Dashboard.
// https://developers.facebook.com/apps

// Enable Facebook login
$config['auth']['facebook']['enabled'] = false;

// Enter the App Id
$config['auth']['facebook']['appId'] = 'copy from Facebook console';

// Enter the App Secret
$config['auth']['facebook']['appSecret'] = 'copy from Facebook console';

// OneBody login requires a URL, Admin user's email address and their API Key
// https://github.com/churchio/onebody/wiki/API

$config['auth']['onebody']['email'] = 'adminuser@example.com';

$config['auth']['onebody']['apiKey'] = 'copy from OneBody console';

$config['auth']['onebody']['url'] = 'https://example.com';

// ------------------------ RENAME FILE: ------------------------
//  rename this file to "auth.php"

// ---------------------- CONFIG COMPLETE: ----------------------
//  copy all files to your webserver and navigate to "install.php"
