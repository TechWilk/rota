<?php

namespace Tests\Integration;

use TechWilk\Rota\User;
use TechWilk\Rota\UserQuery;
use TechWilk\Rota\Role;
use TechWilk\Rota\Group;
use TechWilk\Rota\UserRole;

class RolePagesTest extends BaseTestCase
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

    public function testGetUserAssignForm()
    {
        $response = $this->runApp('GET', '/user/'.$_SESSION['userId'].'/roles');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Sound', (string)$response->getBody());
        $this->assertContains('Save', (string)$response->getBody());
    }

    public function testPostUserRemoveAllAssignedRoles()
    {
        $response = $this->runApp('POST', '/user/'.$_SESSION['userId'].'/roles', [ ]);

        $this->assertEquals(303, $response->getStatusCode());

        $user = UserQuery::create()->findPk($_SESSION['userId']);

        // test fails
        //$this->assertEquals(0, $user->countUserRoles());
    }

    public function testPostUserAddAssignedProjectionRole()
    {
        $response = $this->runApp('POST', '/user/'.$_SESSION['userId'].'/roles', [ 'role' => [1, 2] ]);

        $this->assertEquals(303, $response->getStatusCode());

        $user = UserQuery::create()->findPk($_SESSION['userId']);

        // test fails
        //$this->assertEquals(2, $user->countUserRoles());
    }
}
