<?php

namespace TechWilk\Rota\AuthProvider\Callback;

use TechWilk\Rota\AuthProvider\CallbackInterface;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use TechWilk\Rota\EmailAddress;

class FacebookAuth implements CallbackInterface
{
    protected $facebook;
    protected $enabled;

    protected $appId;
    protected $permissions;

    protected $router;

    protected $user;

    public function __construct(Facebook $facebook, $appId, $baseUrl, $router, $enabled = true)
    {
        $this->facebook = $facebook;
        $this->enabled = (bool) $enabled;

        $this->appId = $appId;
        $this->router = $router;
        $this->baseUrl = $baseUrl;

        $this->permissions = ['email'];
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function getCallbackUrl()
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        $path = $this->router->pathFor('login-callback', ['provider' => $this->getAuthProviderSlug()]);

        $url = $this->baseUrl . $path;

        return $helper->getLoginUrl($url, $this->permissions);
    }

    public function verifyCallback($args)
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: '.$e->getMessage();
            return false;
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: '.$e->getMessage();
            return false;
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
            return false;
        }

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $this->facebook->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->appId); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                echo '<p>Error getting long-lived access token: '.$helper->getMessage()."</p>\n\n";
                return false;
            }
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;

        return true;
    }

    public function getAuthProviderSlug()
    {
        return 'facebook';
    }

    public function getUserId()
    {
        $accessToken = $_SESSION['fb_access_token'];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->facebook->get('/me?fields=id,name,email', $accessToken);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: '.$e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: '.$e->getMessage();
            exit;
        }

        $user = $response->getGraphUser();

        $this->user = $user;

        return $user->getId();
    }

    public function getMeta()
    {
        if (empty($this->user)) {
            $this->getUserId();
        }

        return [
            'name' => $this->user->getName(),
            'email' => new EmailAddress($this->user->getEmail()),
        ];
    }
}
