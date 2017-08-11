<?php

include __DIR__.'/../config/database.php';
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');

$serviceContainer->setAdapterClass('default', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
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
$serviceContainer->setConnectionManager('default', $manager);

$serviceContainer->setAdapterClass('test', 'sqlite');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
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
$manager->setName('test');
$serviceContainer->setConnectionManager('test', $manager);

$serviceContainer->setDefaultDatasource('default');
