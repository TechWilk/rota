<?php

namespace Tests\Integration;

use TechWilk\Rota\SettingsQuery;
use TechWilk\Rota\UserQuery;

class InstallationTest extends BaseTestCase
{
    protected $installDatabase = false;

    /**
     * Ensure database is empty.
     */
    public function setUp()
    {
        $users = UserQuery::create()->find();
        if (!empty($users)) {
            foreach ($users as $user) {
                $user->delete();
            }
        }

        $settings = SettingsQuery::create()->find();
        if (!empty($settings)) {
            foreach ($settings as $row) {
                $row->delete();
            }
        }
    }

    /**
     * Test that the installation page will load when we have no users in the database.
     */
    public function testGetInstallPage()
    {
        $response = $this->runApp('GET', '/install');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Install database', (string) $response->getBody());
        $this->assertContains('Create first user', (string) $response->getBody());
    }

    /**
     * Test that the installation page will load when we have no users in the database.
     */
    public function testGetInstallDatabase()
    {
        $schemaFilePath = __DIR__.'/../../build/sql/default.sql';
        if (file_exists($schemaFilePath)) {
            unlink($schemaFilePath);
        }

        $response = $this->runApp('GET', '/install/database');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(true, file_exists($schemaFilePath), 'Schema file does not exist');

        $schema = file_get_contents($schemaFilePath);

        $this->assertContains('CREATE TABLE', $schema);
    }

    /**
     * Test that the installation page will load when we have no users in the database.
     */
    public function testGetInstallFirstUserPage()
    {
        $userCount = UserQuery::create()->count();
        $this->assertEquals(0, $userCount);

        $response = $this->runApp('GET', '/install/user');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('You don\'t currently have an account.', (string) $response->getBody());
        $this->assertContains('First Name', (string) $response->getBody());
        $this->assertContains('Last Name', (string) $response->getBody());
    }

    /**
     * Test that the index route won't accept a post request.
     */
    public function testPostInstallFirstUserPage()
    {
        $userCount = UserQuery::create()->count();
        $this->assertEquals(0, $userCount);

        $tokens = $this->getCsrfTokensForUri('/install/user');
        $params = [
            'firstName' => 'Test',
            'lastName'  => 'User',
            'email'     => 'test@example.com',
        ];
        $params = array_merge($params, $tokens);

        $response = $this->runApp('POST', '/install/user', $params);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Your password is:', (string) $response->getBody());
        $this->assertContains('test@example.com', (string) $response->getBody());
        $this->assertContains('Sign In', (string) $response->getBody());
    }
}
