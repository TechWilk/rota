<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Slim\App;

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Europe/London');

/**
 * Propel ORM config.
 */
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('default', 'sqlite');
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
$manager->setName('default');
$serviceContainer->setConnectionManager('default', $manager);
$serviceContainer->setDefaultDatasource('default');

$sqlManager = new \Propel\Generator\Manager\SqlManager();
$sqlManager->setConnections(
      ['default' => [
          'dsn'     => 'sqlite:/var/tmp/test.db',
          'adapter' => 'sqlite',
      ],
      ]
);
$sqlManager->setWorkingDirectory(__DIR__.'/../../generated-sql');
$sqlManager->insertSql();

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends TestCase
{
}
