<?php

namespace TechWilk\Rota\Controller;

use Locale;
use Propel\Generator\Application;
use Propel\Runtime\Propel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
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
        $this->logger->info("Fetch install GET '/install'");

        $stage = 1;

        $configPath = __DIR__ . '/../../../config';
        if (
            file_exists($configPath . '/auth.php')
            && file_exists($configPath . '/database.php')
            && file_exists($configPath . '/email.php')
            && file_exists($configPath . '/recording.php')
        ) {
            $stage = 2;
        }

        try {
            $existingUserCount = UserQuery::create()->count();
            if ($existingUserCount > 0) {
                return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('login'));
            }
            $stage = 3;
        } catch (\Propel\Runtime\Exception\PropelException $e) {
        }

        return $this->view->render($response, 'install.twig', ['stage' => $stage]);


        //return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('install-database'));
    }

    public function getInstallDatabase(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->logger->info("Fetch database install GET '/install/database'");

        try {
            $existingUserCount = UserQuery::create()->count();
            if ($existingUserCount > 0) {
                return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('login'));
            }
        } catch (\Propel\Runtime\Exception\PropelException $e) {
            if ($e->getPrevious()->getCode() !== '42S02') {
                return $response;
            }
        }

        $site = new Site();
        $config = $site->getConfig();

        $propelGenerator = new Application('Propel', Propel::VERSION);
        $propelGenerator->setAutoExit(false);
        $propelGenerator->add(new \Propel\Generator\Command\SqlBuildCommand());
        $propelGenerator->add(new \Propel\Generator\Command\SqlInsertCommand());

        $output = new BufferedOutput();

        $input = new ArrayInput([
            'command'      => 'sql:build',
            '-vvv'         => true,
            '--overwrite'  => true,
            '--config-dir' => __DIR__.'/../../../generated-conf',
            '--schema-dir' => __DIR__.'/../../../',
            '--output-dir' => __DIR__.'/../../../build/sql',
        ]);
        $propelGenerator->run($input, $output);

        $input = new ArrayInput([
            'command'      => 'sql:insert',
            '-vvv'         => true,
            '--config-dir' => __DIR__.'/../../../generated-conf',
            '--sql-dir'    => __DIR__.'/../../../build/sql',
        ]);
        $propelGenerator->run($input, $output);

        $outputString = $output->fetch();
        $this->logger->info($outputString);

        // check it was a success
        try {
        $existingUserCount = UserQuery::create()->count();
        } catch (\Propel\Runtime\Exception\PropelException $e) {
            if ($e->getPrevious()->getCode() === '42S02') {
                return $response->getBody()->write('Error installing database:' . "\n" . $outputString);
            }
        }


        return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('install'));
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
        $this->logger->info("Create first user POST '/install/user'");

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
            'message'  => 'Your password is: '.$password.' Copy it somewhere safe as it will not be displayed again. You can change it once you\'ve logged in.',
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
        $path = $this->router->pathFor('home');
        $url = $site->getUrl()['base'];
        $url .= $path === '/' ? '' : $path;
        $settings->setSiteUrl($url);

        $settings->setOwner('Rota');

        $settings->setTimeZone(date_default_timezone_get());
        $settings->setLangLocale(class_exists('Locale') ? Locale::getDefault() : 'en_GB');

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
