<?php

namespace TechWilk\Rota;

use Facebook;

function facebookIsEnabled()
{
    if (siteConfig()['auth']['facebook']['enabled'] == true) {
        return true;
    } else {
        return false;
    }
}

function facebook()
{
    $fb = new Facebook\Facebook([
        'app_id'                => siteConfig()['auth']['facebook']['appId'],
        'app_secret'            => siteConfig()['auth']['facebook']['appSecret'],
        'default_graph_version' => 'v2.2',
    ]);

    return $fb;
}

function getFacebookUserAccessToken($fb)
{
    if (isset($_SESSION['fb_access_token'])) {
        return $_SESSION['fb_access_token'];
    }

    $helper = $fb->getRedirectLoginHelper();

    try {
        $accessToken = $helper->getAccessToken();
        echo 'Access token: ';
        echo var_dump($accessToken);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: '.$e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: '.$e->getMessage();
        exit;
    }

    if (!isset($accessToken)) {
        if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo 'Error: '.$helper->getError()."\n";
            echo 'Error Code: '.$helper->getErrorCode()."\n";
            echo 'Error Reason: '.$helper->getErrorReason()."\n";
            echo 'Error Description: '.$helper->getErrorDescription()."\n";
        } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
        }
        exit;
    }
    // The OAuth 2.0 client handler helps us manage access tokens
    $oAuth2Client = $fb->getOAuth2Client();

    // Get the access token metadata from /debug_token
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);

    // Validation (these will throw FacebookSDKException's when they fail)
    $tokenMetadata->validateAppId($config['auth']['facebook']['appId']); // Replace {app-id} with your app id
  // If you know the user ID this access token belongs to, you can validate it here
  //$tokenMetadata->validateUserId('123');
    $tokenMetadata->validateExpiration();

    if (!$accessToken->isLongLived()) {
        // Exchanges a short-lived access token for a long-lived one
        try {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo '<p>Error getting long-lived access token: '.$helper->getMessage()."</p>\n\n";
            exit;
        }
    }

    $_SESSION['fb_access_token'] = (string) $accessToken;

    return $accessToken;
}

function createFacebookNotificationForUser($userId, $url, $message)
{
    if (!facebookIsEnabled()) {
        return false;
    }

    if (!userIsLinkedToPlatform($userId, 'facebook')) {
        return false;
    }
    $facebookUserId = userSocialIdForPlatform($userId, 'facebook');

    createFacebookNotificationForFacebookUser($facebookUserId, $url, $message);
}

function createFacebookNotificationForFacebookUser($facebookUserId, $url, $message)
{
    if (!facebookIsEnabled()) {
        return false;
    }

    if (strlen($message) > 180) {
        $message = substr($message, 0, 180);
    }

    $fb = facebook();
    $appAccessToken = $fb->getApp()->getAccessToken();

    try {
        $fb->setDefaultAccessToken($appAccessToken);
    } catch (\Facebook\Exceptions\FacebookAuthenticationException $e) {
        insertStatistics('system', __FILE__, 'fb-auth-error', $e->getMessage());

        return false;
    }
    $sendNotif = $fb->post('/'.$facebookUserId.'/notifications', ['href' => $url, 'template' => $message], $appAccessToken);

    if ($sendNotif->getHttpStatusCode() == 200) {
        return true;
    } else {
        return false;
    }
}
