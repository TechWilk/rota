<?php

namespace Tests\Integration;

use TechWilk\Rota\User;

class DashboardTest extends BaseTestCase
{
    /**
     * Ensure user is logged in before tests run.
     */
    public static function setUpBeforeClass()
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setFirstName('Test');
        $user->setLastName('User');
        $user->setIsAdmin(true);
        $user->save();

        $_SESSION['userId'] = $user->getId();
    }

    /**
     * Logout user is after tests run.
     */
    public static function tearDownAfterClass()
    {
        unset($_SESSION['userId']);
    }

    /**
     * Test that the index route returns a rendered response containing the text 'Dashbpard', 'Totals' and 'view all'.
     */
    public function testGetHomepageBeforeAuthForRedirect()
    {
        $response = $this->runApp('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Dashboard', (string) $response->getBody());
        $this->assertContains('Upcoming Events', (string) $response->getBody());
        //$this->assertNotContains('Hello', (string)$response->getBody());
    }

    /**
     * Test that the index route won't accept a post request.
     */
    public function testPostHomepageForRedirect()
    {
        $response = $this->runApp('POST', '/', ['test']);

        $this->assertEquals(404, $response->getStatusCode());
        //$this->assertContains('Method not allowed', (string)$response->getBody());
    }
}
