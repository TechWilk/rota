<?php

require __DIR__.'/../config/database.php';

return [
    'propel' => [
        'database' => [
            'connections' => [
                'default' => [
                    'adapter'    => 'mysql',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'mysql:host='.$config['db']['host'].';dbname='.$config['db']['dbname'],
                    'user'       => $config['db']['user'],
                    'password'   => $config['db']['pass'],
                    'attributes' => [],
                ],
            ],
        ],
        'runtime' => [
            'defaultConnection' => 'default',
            'connections'       => ['default'],
        ],
        'generator' => [
            'defaultConnection' => 'default',
            'connections'       => ['default'],
        ],
    ],
];
