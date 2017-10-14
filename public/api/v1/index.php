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

if (isAdmin()):

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Start of API
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

require_once dirname(__FILE__).'/../../../vendor/autoload.php';

include_once dirname(__FILE__).'/../../../config/database.php';

// Create and configure Slim app
$app = new \Slim\App(['settings' => $config]);

spl_autoload_register(function ($classname) {
    require dirname(__FILE__).'/../../../api-classes/'.$classname.'.php';
});

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

// ~~~~~~~~~~~~~~~ Event ~~~~~~~~~~~~~~~

$app->post('/events', function ($request, $response, $args) {
    $postData = $request->getParsedBody();

    $name = filter_var($postData['name'], FILTER_SANITIZE_STRING);

    $event = new Event();

    $data = ['id' => 2, 'name' => 'event name'];

    return $response->withJson($data);
});

$app->get('/events/{id}', function ($request, $response, $args) {
    $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

    $event = new Event();

    try {
        $event->getFromDbWithId($this->db, $id);
    } catch (Exception $e) {
        return $response->withJson(['errors' => ['status' => '404', 'title' => 'Specified event cannot be found in the databse']], 404);
    }

    $series = $event->getSeries();
    $type = $event->getType();
    $sub_type = $event->getSubType();
    $location = $event->getLocation();

    $data = [
          'data' => [
                    'id'            => $event->getId(),
                    'name'          => $event->name,
                    'date'          => $event->datetime,
                    'notes'         => $event->notes,
                    'bible_verse'   => $event->bible_verse,
                    'relationships' => [
                                        'series' => [
                                                    'links' => [
                                                                'self'    => '/events/'.$event->getId().'/relationships/series',
                                                                'related' => '/events/'.$event->getId().'/series/'.$series->getId(),
                                                    ],
                                                    'data' => [
                                                      'type' => 'series',
                                                      'id'   => $series->getId(),
                                                    ],
                                        ],
                                        'types' => [
                                                    'links' => [
                                                                'self'    => '/events/'.$event->getId().'/relationships/types',
                                                                'related' => '/events/'.$event->getId().'/types/'.$type->getId(),
                                                    ],
                                                    'data' => [
                                                      'type' => 'types',
                                                      'id'   => $type->getId(),
                                                    ],
                                        ],
                                        'sub-types' => [
                                                    'links' => [
                                                                'self'    => '/events/'.$event->getId().'/relationships/sub-types',
                                                                'related' => '/events/'.$event->getId().'/sub-types/'.$sub_type->getId(),
                                                    ],
                                                    'data' => [
                                                                'type' => 'sub-types',
                                                                'id'   => $sub_type->getId(),
                                                    ],
                                        ],
                                        'location' => [
                                                    'links' => [
                                                                'self'    => '/events/'.$event->getId().'/relationships/locations',
                                                                'related' => '/events/'.$event->getId().'/locations/'.$location->getId(),
                                                    ],
                                                    'data' => [
                                                                'type' => 'locations',
                                                                'id'   => $location->getId(),
                                                    ],
                                        ],
                    ],
          ],
          'included' => [
                          [
                            'type'       => 'series',
                            'id'         => $series->getId(),
                            'attributes' => [
                                          'name'        => $series->name,
                                          'description' => $series->description,
                            ],
                            'links' => [
                                        'self' => '/series/'.$series->getId(),
                            ],
                          ],
                          [
                            'type'       => 'types',
                            'id'         => $type->getId(),
                            'attributes' => [
                                      'name'        => $type->name,
                                      'description' => $type->description,
                            ],
                            'links' => [
                                        'self' => '/types/'.$type->getId(),
                            ],
                          ],
                          [
                            'type'       => 'sub-types',
                            'id'         => $sub_type->getId(),
                            'attributes' => [
                                      'name'        => $sub_type->name,
                                      'description' => $sub_type->description,
                            ],
                            'links' => [
                                        'self' => '/sub-types/'.$sub_type->getId(),
                            ],
                          ],
                          [
                            'type'       => 'locations',
                            'id'         => $location->getId(),
                            'attributes' => [
                                      'name' => $location->name,
                            ],
                            'links' => [
                                        'self' => '/locations/'.$location->getId(),
                            ],
                          ],
          ],
          'meta' => [
                    'status' => '200',
          ],
  ];

    return $response->withJson($data, 200);
});

// ~~~~~~~~~~~~~~~ Series ~~~~~~~~~~~~~~~

$app->post('/series', function ($request, $response, $args) {
    $postData = $request->getParsedBody();

    $name = filter_var($postData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($postData['description'], FILTER_SANITIZE_STRING);

    $series = new Series();
    $series->name = $name;
    $series->description = $description;

    $series->createInDb($this->db);

    $data = [
          'data' => [
                    'id'          => $series->getId(),
                    'name'        => $series->name,
                    'description' => $series->description,
                    ],
          ];

    return $response->withJson($data);
});

$app->get('/series/{id}', function ($request, $response, $args) {
    $id = (int) filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

    $series = new Series();
    $series->getFromDbWithId($this->db, $id);

    $data = [
          'data' => [
                    'id'          => $series->getId(),
                    'name'        => $series->name,
                    'description' => $series->description,
                    ],
          ];

    return $response->withJson($data);
});

$app->delete('/series/{id}', function ($request, $response, $args) {
    $id = (int) filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

    $series = new Series();
    $series->deleteFromDbWithId($this->db, $id);

    $data = [
          'data' => [
                    'id'       => $series->getId(),
                    'archived' => true,
                    ],
          ];

    return $response->withJson($data);
});

// ~~~~~~~~~~~~~~~ Type ~~~~~~~~~~~~~~~

$app->post('/types', function ($request, $response, $args) {
    $postData = $request->getParsedBody();

    $name = filter_var($postData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($postData['description'], FILTER_SANITIZE_STRING);

    $type = new Type();
    $type->name = $name;
    $type->description = $description;

    $type->createInDb($this->db);

    $data = [
          'data' => [
                    'id'          => $type->getId(),
                    'name'        => $type->name,
                    'description' => $type->description,
                    ],
          ];

    return $response->withJson($data);
});

$app->get('/types/{id}', function ($request, $response, $args) {
    $id = (int) filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

    $type = new Type();
    $type->getFromDbWithId($this->db, $id);

    $data = [
          'data' => [
                    'id'          => $type->getId(),
                    'name'        => $type->name,
                    'description' => $type->description,
                    ],
          ];

    return $response->withJson($data);
});

$app->delete('/types/{id}', function ($request, $response, $args) {
    $id = (int) filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

    $type = new Type();
    $type->deleteFromDbWithId($this->db, $id);

    $data = [
          'data' => [
                    'id'       => $type->getId(),
                    'archived' => true,
                    ],
          ];

    return $response->withJson($data);
});

// ~~~~~~~~~~~~~~~ Sub Type ~~~~~~~~~~~~~~~

$app->post('/sub-types', function ($request, $response, $args) {
    $postData = $request->getParsedBody();

    $name = filter_var($postData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($postData['description'], FILTER_SANITIZE_STRING);

    $type = new SubType();
    $type->name = $name;
    $type->description = $description;

    $type->createInDb($this->db);

    $data = [
          'data' => [
                    'id'          => $type->getId(),
                    'name'        => $type->name,
                    'description' => $type->description,
                    ],
          ];

    return $response->withJson($data);
});

$app->get('/sub-types/{id}', function ($request, $response, $args) {
    $id = (int) filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

    $type = new SubType();
    $type->getFromDbWithId($this->db, $id);

    $data = [
          'data' => [
                    'id'          => $type->getId(),
                    'name'        => $type->name,
                    'description' => $type->description,
                    ],
          ];

    return $response->withJson($data);
});

$app->delete('/sub-types/{id}', function ($request, $response, $args) {
    $id = (int) filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

    $type = new SubType();
    $type->deleteFromDbWithId($this->db, $id);

    $data = [
          'data' => [
                    'id'       => $type->getId(),
                    'archived' => true,
                    ],
          ];

    return $response->withJson($data);
});

// ~~~~~~~~~~~~~~~ Location ~~~~~~~~~~~~~~~

$app->post('/locations', function ($request, $response, $args) {
    $postData = $request->getParsedBody();

    $name = filter_var($postData['name'], FILTER_SANITIZE_STRING);

    $location = new Location();
    $location->name = $name;

    $type->createInDb($this->db);

    $data = [
          'data' => [
                    'id'   => $location->getId(),
                    'name' => $location->name,
                    ],
          ];

    return $response->withJson($data);
});

$app->get('/locations/{id}', function ($request, $response, $args) {
    $id = (int) filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

    $location = new Location();
    $location->getFromDbWithId($this->db, $id);

    $data = [
          'data' => [
                    'id'   => $location->getId(),
                    'name' => $location->name,
                    ],
          ];

    return $response->withJson($data);
});

$app->delete('/locations/{id}', function ($request, $response, $args) {
    $id = (int) filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

    $location = new Location();
    $location->deleteFromDbWithId($this->db, $id);

    $data = [
          'data' => [
                    'id'       => $location->getId(),
                    'archived' => true,
                    ],
          ];

    return $response->withJson($data);
});

// Run app
$app->run();

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// End of very very very basic auth
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

else:
http_response_code(401);

endif;
