<?php

namespace TechWilk\Rota\AuthProvider\UsernamePassword;

use TechWilk\Rota\UsernamePasswordInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class OneBodyAuth implements UsernamePasswordInterface
{
    protected $guzzle;
    protected $enabled = true;

    protected $data;

    public function __construct(Client $guzzle, $enabled)
    {
        $this->guzzle = $guzzle;
        $this->enabled = (bool)$enabled;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function checkCredentials($username, $password)
    {
        $request = new Request('POST', 'login', [
            'form_params' => [
                'username' => $username,
                'password' => $password,
            ]
        ]);
        $response = $client->send($request, ['timeout' => 2]);

        if ($response->getStatusCode() != 200) {
            return false;
        }
        
        $this->data = new SimpleXMLElement($response->getBody()->getContents());

        if ($this->data->deleted == true || $this->status != 'active') {
            return false;
        }

        return true;
    }
}
