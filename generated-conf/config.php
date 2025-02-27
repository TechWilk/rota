<?php

require __DIR__.'/../config/database.php';
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion(2);

$serviceContainer->setAdapterClass('default', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle('default');
$manager->setConfiguration([
    'classname'  => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
    'dsn'        => 'mysql:host='.$config['db']['host'].';dbname='.$config['db']['dbname'],
    'user'       => $config['db']['user'],
    'password'   => $config['db']['pass'],
    'attributes' => [
        'ATTR_EMULATE_PREPARES' => false,
        'ATTR_TIMEOUT'          => 30,
    ],
    'model_paths' => [
        0 => 'src',
        1 => 'vendor',
    ],
]);
$manager->setName('default');
$serviceContainer->setConnectionManager($manager);

$serviceContainer->setAdapterClass('test', 'sqlite');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle('test');
$manager->setConfiguration([
    'classname'  => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
    'dsn'        => 'sqlite:/var/tmp/test.db',
    'attributes' => [
        'ATTR_EMULATE_PREPARES' => false,
        'ATTR_TIMEOUT'          => 30,
    ],
    'model_paths' => [
        0 => 'src',
        1 => 'vendor',
    ],
]);
$serviceContainer->setConnectionManager($manager);

$serviceContainer->setDefaultDatasource('default');
require_once __DIR__.'/./loadDatabase.php';
