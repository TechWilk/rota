<?php

namespace Tests\Integration;

use TechWilk\Rota\User;
use TechWilk\Rota\UserQuery;
use TechWilk\Rota\Role;
use TechWilk\Rota\Group;
use TechWilk\Rota\UserRole;

class UserPagesTest extends BaseTestCase
{
    /**
    * Ensure user is logged in before tests run
    */
    public static function setUpBeforeClass()
    {
        $user = new User;
        $user->setEmail('test@example.com');
        $user->setFirstName('Test');
        $user->setLastName('User');
        $user->setIsAdmin(true);
        $user->setPassword('testPassword123');
        $user->save();

        $group = new Group();
        $group->setName('Tech');
        $group->setDescription('Technical Team');
        $group->save();

        $role = new Role;
        $role->setName('Sound');
        $role->setDescription('Live sound operator');
        $role->setGroup($group);
        $role->save();

        $role = new Role;
        $role->setName('Projection');
        $role->setDescription('Live visuals operator');
        $role->setGroup($group);
        $role->save();

        $userRole = new UserRole;
        $userRole->setUser($user);
        $userRole->setRole($role);
        $userRole->save();

        // log in the user
        $_SESSION['userId'] = $user->getId();
    }

    /**
    * Logout user is after tests run
    */
    public static function tearDownAfterClass()
    {
        unset($_SESSION['userId']);
    }

    /**
    * Test that the index route returns a rendered response containing the text 'Dashboard', 'Totals' and 'view all'
    */
    public function testGetCurrentUser()
    {
        $response = $this->runApp('GET', '/user/'.$_SESSION['userId']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test User', (string)$response->getBody());
        $this->assertContains('test@example.com', (string)$response->getBody());
        //$this->assertNotContains('Hello', (string)$response->getBody());
    }

    /**
    * Test that the index route won't accept a post request
    */
    public function testPostCurrentUser()
    {
        $response = $this->runApp('POST', '/user/'.$_SESSION['userId'], ['firstname' => 'Test', 'lastname' => 'User', 'email' => 'test@example.com', 'mobile' => '01234567890']);

        $this->assertEquals(303, $response->getStatusCode());
        //$this->assertContains('Method not allowed', (string)$response->getBody());
    }

    /**
    * Test that the index route won't accept a post request
    */
    public function testGetAllUsers()
    {
        $response = $this->runApp('GET', '/users');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test User', (string)$response->getBody());
        $this->assertContains('test@example.com', (string)$response->getBody());
        //$this->assertContains('Method not allowed', (string)$response->getBody());
    }

    /**
    * Test that the index route won't accept a post request
    */
    public function testPostAllUsersFails()
    {
        $response = $this->runApp('POST', '/users', ['test']);

        $this->assertEquals(404, $response->getStatusCode());
        //$this->assertContains('Method not allowed', (string)$response->getBody());
    }

    public function testGetNewUserForm()
    {
        $response = $this->runApp('GET', '/user/new');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Save', (string)$response->getBody());
    }

    public function testPostNewUser()
    {
        $response = $this->runApp('POST', '/user', ['firstname' => 'Bob', 'lastname' => 'Jones', 'email' => 'bob.jones@example.com', 'mobile' => '01234098765']);

        $this->assertEquals(303, $response->getStatusCode());
    }

    public function testGetUserEditForm()
    {
        $response = $this->runApp('GET', '/user/'.$_SESSION['userId'].'/edit');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test', (string)$response->getBody());
        $this->assertContains('User', (string)$response->getBody());
        $this->assertContains('01234567890', (string)$response->getBody()); // set in testPostCurrentUser()
        $this->assertContains('test@example.com', (string)$response->getBody());
        $this->assertContains('Save', (string)$response->getBody());
    }

    public function testGetUserWidgetForCurrentUser()
    {
        $response = $this->runApp('GET', '/user/'.$_SESSION['userId'].'/widget-only');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test', (string)$response->getBody());
        $this->assertContains('User', (string)$response->getBody());
        $this->assertContains('test@example.com', (string)$response->getBody());
        $this->assertContains('roles', (string)$response->getBody());
        $this->assertContains('Calendar Syncing', (string)$response->getBody());
    }

    public function testGetUserPasswordForm()
    {
        $response = $this->runApp('GET', '/user/'.$_SESSION['userId'].'/password');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test', (string)$response->getBody());
        $this->assertContains('User', (string)$response->getBody());
        $this->assertContains('password', (string)$response->getBody());
        $this->assertContains('Save', (string)$response->getBody());
    }

    public function testChangeUserPasswordIncorrectExistingPassword()
    {
        $response = $this->runApp('POST', '/user/'.$_SESSION['userId'].'/password', [ 'existing' => 'this-is-wrong', 'new' => 'newPassword123', 'confirm' => 'newPassword123']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Existing password not correct', (string)$response->getBody());
        $this->assertNotContains('New passwords did not match', (string)$response->getBody());

        $user = UserQuery::create()->findPk($_SESSION['userId']);
        $this->assertFalse($user->checkPassword('newPassword123'));
    }

    public function testChangeUserPasswordNewPasswordsDoNotMatch()
    {
        $response = $this->runApp('POST', '/user/'.$_SESSION['userId'].'/password', [ 'existing' => 'this-is-testPassword123wrong', 'new' => 'does-not-match', 'confirm' => 'newPassword123']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotContains('Existing password not correct', (string)$response->getBody());
        $this->assertContains('New passwords did not match', (string)$response->getBody());

        $user = UserQuery::create()->findPk($_SESSION['userId']);
        $this->assertFalse($user->checkPassword('newPassword123'));
    }

    public function testChangeUserPassword()
    {
        $response = $this->runApp('POST', '/user/'.$_SESSION['userId'].'/password', [ 'existing' => 'testPassword123', 'new' => 'newPassword123', 'confirm' => 'newPassword123']);

        $this->assertEquals(303, $response->getStatusCode());

        $user = UserQuery::create()->findPk($_SESSION['userId']);
        $this->assertTrue($user->checkPassword('newPassword123'));
    }
}
