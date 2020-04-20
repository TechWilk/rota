<?php

namespace TechWilk\Rota;

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Very very very basic auth
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

session_start();

function isAdmin()
{
    if ($_SESSION['isAdmin'] == '1') {
        return true;
    } else {
        return false;
    }
}

if (isAdmin()) {

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    // Start of API
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    require_once __DIR__.'/../../../vendor/autoload.php';

    require_once __DIR__.'/../../../generated-conf/config.php';

    // Create and configure Slim app
    $app = new \Slim\App(['settings' => $config]);

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    // Containers for DI
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    $container = $app->getContainer();

    $container['db'] = function ($c) {
        $db_config = $c['settings']['db'];
        $db = new Database($db_config);

        return $db;
    };

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    // Define app routes
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    // ~~~~~~~~~~~~~~~ Series ~~~~~~~~~~~~~~~

    $app->post('/series', function ($request, $response, $args) {
        $postData = $request->getParsedBody();

        $name = filter_var($postData['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($postData['description'], FILTER_SANITIZE_STRING);

        $series = new EventGroup();
        $series->setName($name);
        $series->setDescription($description);
        $series->save();

        $data = [
            'data' => [
                'id'          => $series->getId(),
                'name'        => $series->getName(),
                'description' => $series->getDescription(),
            ],
        ];

        return $response->withJson($data);
    });

    // Run app
    $app->run();

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// End of very very very basic auth
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
} else {
    http_response_code(401);
}
