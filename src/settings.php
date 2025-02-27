<?php

require_once __DIR__.'/config.php';

$config = getConfig();

return [
    'settings' => [
        'displayErrorDetails'               => $config['displayErrorDetails'] ?? false, // set to false in production
        'addContentLengthHeader'            => false, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => true, // Only set this if you need access to route within middleware

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__.'/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name'  => 'rota',
            'path'  => __DIR__.'/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Database settings
        'db' => [
            'name' => $config['db']['dbname'],
            'host' => $config['db']['host'],
            'user' => $config['db']['user'],
            'pass' => $config['db']['pass'],
        ],
    ],
];
