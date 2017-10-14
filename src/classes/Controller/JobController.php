<?php

namespace TechWilk\Rota\Controller;

use DateTime;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TechWilk\Rota\Site;

class JobController extends BaseController
{
    public function getDaily(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event GET '/jobs/daily'");
        $site = new Site();
        $actualToken = $site->getSettings()->getToken();
        if ($args['token'] !== $actualToken) {
            return $response;
        }

        $client = new Client();
        $url = $site->getUrl()['base'] . $this->router->pathFor('home').'old/cr_daily.php';
        $response = $client->get($url, [
            'query' => [
                'token' => $args['token'],
            ],
        ]);

        return $response;
    }
}
