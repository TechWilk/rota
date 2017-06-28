<?php

#
# Emails can be sent using two different methods.
#
# 1. Mailgun (recommended)
#    Uses a third party email sending service which provides comprehensive logging and
#    useful error messages. FREE for first 10000 emails per month
#
# 2. Sendmail (advanced)
#    Provided as a fallback if you are unable to use Mailgun (for whatever reason).
#    Not recommended as it may require additional setup depending on hosting providor
#    and you are responsible for ensuring emails are delivered and not marked as spam.

# Service to use to send emails (either mailgun or sendmail)
$config['email']['method'] = 'mailgun';

# Mailgun requires an API Base URL and API Key generated through the Mailgun Dashboard.
# https://mailgun.com/

# Enter the API Base URL
$config['email']['mailgun']['apiBaseUrl'] = 'https://api.mailgun.net/v3/example.com';

# Enter the API Key
$config['email']['mailgun']['apiKey'] = 'ef8367aba0817c249998139700a373a0';


# ------------------------ RENAME FILE: ------------------------
#  rename this file to "email.php"

# ---------------------- CONFIG COMPLETE: ----------------------
#  copy all files to your webserver and navigate to "install.php"
