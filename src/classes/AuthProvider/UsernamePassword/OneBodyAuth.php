<?php

namespace TechWilk\Rota\AuthProvider\UsernamePassword;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use SimpleXMLElement;
use TechWilk\Rota\AuthProvider\UsernamePasswordInterface;
use TechWilk\Rota\EmailAddress;

class OneBodyAuth implements UsernamePasswordInterface
{
    protected $guzzle;
    protected $enabled;

    protected $adminEmail;
    protected $apiKey;

    protected $data;

    public function __construct(Client $guzzle, EmailAddress $adminEmail, $apiKey, $enabled = true)
    {
        $this->guzzle = $guzzle;
        $this->enabled = (bool) $enabled;
        $this->adminEmail = $adminEmail;
        $this->apiKey = $apiKey;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function checkCredentials($username, $password)
    {
        try {
            $response = $this->guzzle->post('authentications', [
                'form_params' => [
                    'authentication' => [
                        'email'    => strval($username),
                        'password' => $password,
                    ],

                ],
                'auth' => [
                    $this->adminEmail,
                    $this->apiKey,
                ],
                'timeout' => 2,
            ]);
        } catch (ClientException $e) {
            return false;
        }

        if ($response->getStatusCode() != 201) {
            return false;
        }

        $this->data = new SimpleXMLElement($response->getBody()->getContents());

        if ($this->data->status != 'active') {
            return false;
        }

        return true;
    }

    public function getResetPasswordUrl()
    {
        $url = null;
        $base_uri = $this->guzzle->getConfig()['base_uri'];

        if (strlen($base_uri) > 0) {
            $url = $base_uri.'/account/new?forgot=true';
        }

        return $url;
    }

    public function getAuthProviderSlug()
    {
        return 'onebody';
    }

    public function getUserId()
    {
        return is_int((int) $this->data->id) ? (int) $this->data->id : null;
    }

    public function getMeta()
    {
        return $this->data;
    }
}
