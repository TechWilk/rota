<?php

namespace TechWilk\Rota;

use Aptoma\Twig\Extension\MarkdownEngine;
use Aptoma\Twig\Extension\MarkdownExtension;
use DPolac\TwigLambda\LambdaExtension;
use Facebook;
use GuzzleHttp;
use Monolog;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use TechWilk\Twig\Extension\LineWrap;
use Twig_Extensions_Extension_Date;
use Twig_Extensions_Extension_Text;

// DIC configuration

$container = $app->getContainer();

// TWIG view renderer
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $view = new Twig($settings['template_path'], [
        'cache' => false, // or 'path/to/cache'
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new TwigExtension($c['router'], $basePath));

    $engine = new MarkdownEngine\MichelfMarkdownEngine();
    $view->addExtension(new MarkdownExtension($engine));

    $view->addExtension(new LambdaExtension());
    $view->addExtension(new Twig_Extensions_Extension_Date());
    $view->addExtension(new Twig_Extensions_Extension_Text());

    $view->addExtension(new LineWrap());

    $env = $view->getEnvironment();
    $env->addGlobal('site', new Site());
    $env->addGlobal('currenturl', $c->get('request')->getUri());
    $env->addGlobal('currentpath', $c->get('request')->getUri()->getBasePath().'/'.$c->get('request')->getUri()->getPath());

    if (isset($_SESSION['userId'])) {
        $u = UserQuery::create()->findPk($_SESSION['userId']);
        if (isset($u)) {
            $env->addGlobal('currentuser', $u);
        }
    }

    return $view;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

$container['auth'] = function ($c) {
    $authConfig = getConfig()['auth'];

    // full domain e.g. https://example.com
    $site = new Site();
    $basePath = $site->getUrl()['base'];

    switch ($authConfig['scheme']) {
        case 'facebook':
            $facebook = new Facebook\Facebook([
                'app_id'                => $authConfig['facebook']['appId'],
                'app_secret'            => $authConfig['facebook']['appSecret'],
                'default_graph_version' => 'v2.2',
            ]);
            $authProvider = new AuthProvider\Callback\FacebookAuth($facebook, $authConfig['facebook']['appId'], $basePath, $c['router']);
            break;

        case 'onebody':
            $url = $authConfig['onebody']['url'].'/';
            $email = new EmailAddress($authConfig['onebody']['email']);
            $apiKey = $authConfig['onebody']['apiKey'];

            $guzzle = new GuzzleHttp\Client(['base_uri' => $url]);
            $authProvider = new AuthProvider\UsernamePassword\OneBodyAuth($guzzle, $email, $apiKey);
            break;

        default:
            $authProvider = new AuthProvider\UsernamePassword\UsernamePasswordAuth();
            break;
    }

    $allowedRoutes = [
        'login',
        'login-post',
        'login-auth',
        'login-callback',
        'sign-up',
        'sign-up-post',
        'sign-up-cancel',
        'user-calendar',
        'job-daily',
        'install',
        'install-user',
        'install-user-post',
    ];

    return new Authentication($c, $authProvider, $allowedRoutes);
};
