<?php

namespace TechWilk\Rota\Controller;

use Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TechWilk\Rota\Crypt;
use TechWilk\Rota\Settings;
use TechWilk\Rota\SettingsQuery;
use TechWilk\Rota\Site;
use TechWilk\Rota\User;
use TechWilk\Rota\UserQuery;

class InstallController extends BaseController
{
    public function getInstall(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('install-user');
    }

    public function getFirstUserForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch first user form GET '/install/user'");

        // don't run if we're already installed
        $existingUserCount = UserQuery::create()->count();
        if ($existingUserCount > 0) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('login'));
        }

        return $this->view->render($response, 'login-sign-up.twig', [
            'message' => 'Your password will be auto-generated and displayed on the next screen.',
        ]);
    }

    public function postFirstUserForm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch first user form GET '/install/user'");

        // don't run if we're already installed
        $existingUserCount = UserQuery::create()->count();
        if ($existingUserCount > 0) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('login'));
        }

        $data = $request->getParsedBody();

        $user = new User();

        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setEmail($data['email']);

        $user->setIsAdmin(true);
        $user->setIsOverviewRecipient(true);
        $user->setRecieveReminderEmails(true);

        $password = Crypt::generateToken(20);
        $user->setPassword($password);

        $user->save();

        $this->populateDefaultSettings();

        return $this->view->render($response, 'login-credentials.twig', [
            'username' => $user->getEmail(),
            'message'  => 'Your password is: '.$password.' Copy it somewhere safe and you can change it once you\'ve logged in.',
        ]);
    }

    protected function populateDefaultSettings()
    {
        // don't run if we're already got settings
        $existingSettings = SettingsQuery::create()->count();
        if ($existingSettings > 0) {
            return;
        }

        $settings = new Settings();

        $site = new Site();
        $url = $site->getUrl()['base'].$this->router->pathFor('home');
        $settings->setSiteUrl($url);

        $settings->setOwner('Rota');

        $settings->setTimeZone(date_default_timezone_get());
        $settings->setLangLocale(Locale::getDefault());

        $settings->setTimeFormatLong('%A, %B %e @ %I:%M %p');
        $settings->setTimeFormatNormal('%d/%m/%y %I:%M %p');
        $settings->setTimeFormatShort('%a, <strong>%b %e</strong>, %I:%M %p');
        $settings->setTimeOnlyFormat('%l %M %p');
        $settings->setDateOnlyFormat('%A');
        $settings->setDayOnlyFormat('%A, %B %e');

        $settings->setNotificationEmail(<<<'EMAIL'
Dear [name]

This is an automatic reminder.

You have the roles: [rotaoutput]
During the service on [date] in [location].
[eventdetails]

If you have arranged a swap, please let us know.

Many thanks for your continued service!
EMAIL
);

        $settings->setToken(Crypt::generateToken(100));
        $settings->setSkin('skin-blue');

        // @todo remove unused legacy settings
        $settings->setDebugMode(false);

        $settings->save();
    }
}
