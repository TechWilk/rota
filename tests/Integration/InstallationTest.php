<?php

namespace Tests\Integration;

use TechWilk\Rota\User;
use TechWilk\Rota\UserQuery;

class InstallationTest extends BaseTestCase
{
    /**
     * Test that the installation page will load when we have no users in the database.
     */
    public function testGetInstallFirstUserPage()
    {
        $response = $this->runApp('GET', '/install/user');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('You do not have an account', (string) $response->getBody());
        $this->assertContains('First Name', (string) $response->getBody());
        $this->assertContains('Last Name', (string) $response->getBody());

        $userCount = UserQuery::create()->count();
        $this->assertEquals(0, $userCount);
    }

    /**
     * Test that the index route won't accept a post request.
     */
    public function testPostCurrentUser()
    {
        $response = $this->runApp('POST', '/user/'.$_SESSION['userId'], ['firstname' => 'Test', 'lastname' => 'User', 'email' => 'test@example.com', 'mobile' => '01234567890']);

        $this->assertEquals(303, $response->getStatusCode());
        //$this->assertContains('Method not allowed', (string)$response->getBody());
    }
}
