<?php

namespace TechWilk\Rota\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TechWilk\Rota\Site;
use TechWilk\Rota\SettingsQuery;
use TechWilk\Rota\Job\SendReminders;

class JobController extends BaseController
{
    public function getDaily(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch event GET '/jobs/daily'");

        // check the token
        $token = $args['token'];

        $site = new Site();
        $actualToken = $site->getSettings()->getToken();

        if ($actualToken->getToken() !== $token) {
            return $response->withStatus(404);
        }

        $job = new SendReminders($this->sender);
        $job->sendReminders();

        return $this->view->render($response, 'job.twig');
    }
}
