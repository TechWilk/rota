<?php

namespace Tests\Integration;

use DOMDocument;
use DOMXPath;
use PHPUnit\Framework\TestCase;
use Propel\Generator\Manager\SqlManager;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Europe/London');

session_start();

/**
 * Propel ORM config.
 */
$serviceContainer = Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('default', 'sqlite');
$manager = new ConnectionManagerSingle();
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

// delete test db (if exists) and create a new one
if (file_exists('/var/tmp/test.db')) {
    unlink('/var/tmp/test.db');
}

$sqlManager = new SqlManager();
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
    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = true;

    protected $csrfTokenFields = [
        'csrf_name',
        'csrf_value',
    ];

    /**
     * Process the application given a request method and URI.
     *
     * @param string            $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string            $requestUri    the request URI
     * @param array|object|null $requestData   the request data
     *
     * @return \Slim\Http\Response
     */
    public function runApp($requestMethod, $requestUri, $requestData = null)
    {
        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI'    => $requestUri,
            ]
        );

        // pretend we're an actual server
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'example.com';

        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        // Set up a response object
        $response = new Response();

        // Use the application settings
        $settings = require __DIR__.'/../../src/settings.php';
        $settings['settings']['logger']['path'] = __DIR__.'/../../logs/test.log';

        // Instantiate the application
        $app = new App($settings);

        // Set up dependencies
        require __DIR__.'/../../src/dependencies.php';

        // Register middleware
        if ($this->withMiddleware) {
            require __DIR__.'/../../src/middleware.php';
        }

        // Register routes
        require __DIR__.'/../../src/routes.php';

        // Process the application
        $response = $app->process($request, $response);

        // Return the response
        return $response;
    }

    public function getCsrfTokensForUri($requestUri)
    {
        $response = $this->runApp('GET', $requestUri);

        $html = (string) $response->getBody();

        $dom = new DOMDocument();
        $dom->validateOnParse = false;
        $dom->recover = true;
        $dom->formatOutput = false;
        $dom->loadHTML($html, LIBXML_NOWARNING);

        $xpath = new DOMXPath($dom);
        foreach ($this->csrfTokenFields as $field) {
            $col = $xpath->query('//input[@name="'.$field.'"]');
            foreach ($col as $node) {
                $csrfTokens[$field] = $node->getAttribute('value');
            }
        }

        if (empty($csrfTokens)) {
            return [];
        }

        return $csrfTokens;
    }
}
