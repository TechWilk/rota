<?php

namespace Tests\Integration;

use TechWilk\Rota\Group;
use TechWilk\Rota\Role;
use TechWilk\Rota\User;
use TechWilk\Rota\UserRole;

class AvailabilityPagesTest extends BaseTestCase
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
        $user->setPassword('testPassword123');
        $user->save();

        $group = new Group();
        $group->setName('Tech');
        $group->setDescription('Technical Team');
        $group->save();

        $role = new Role();
        $role->setName('Sound');
        $role->setDescription('Live sound operator');
        $role->setGroup($group);
        $role->save();

        $role = new Role();
        $role->setName('Projection');
        $role->setDescription('Live visuals operator');
        $role->setGroup($group);
        $role->save();

        $userRole = new UserRole();
        $userRole->setUser($user);
        $userRole->setRole($role);
        $userRole->save();

        // log in the user
        $_SESSION['userId'] = $user->getId();
    }

    /**
     * Logout user is after tests run.
     */
    public static function tearDownAfterClass()
    {
        unset($_SESSION['userId']);
    }

    public function testGetUserAvailabilityForm()
    {
        $response = $this->runApp('GET', '/user/'.$_SESSION['userId'].'/availability');

        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertContains('Available', (string)$response->getBody());
        $this->assertContains('Save', (string) $response->getBody());
    }
}
