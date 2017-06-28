<?php

# Facebook login requires and App Id and Secret generated through the Facebook's App Dashboard.
# https://developers.facebook.com/apps

# Enable Facebook login
$config['auth']['facebook']['enabled'] = false;

# Enter the App Id
$config['auth']['facebook']['appId'] = 'copy from Facebook console';

# Enter the App Secret
$config['auth']['facebook']['appSecret'] = 'copy from Facebook console';


# ------------------------ RENAME FILE: ------------------------
#  rename this file to "auth.php"

# ---------------------- CONFIG COMPLETE: ----------------------
#  copy all files to your webserver and navigate to "install.php"
