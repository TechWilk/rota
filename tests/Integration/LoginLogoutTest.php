<?php

namespace Tests\Integration;

use TechWilk\Rota\User;

class LoginLogoutTest extends BaseTestCase
{
    /**
  * Create an admin user
  */
  public static function setUpBeforeClass()
  {
      $user = new User;
      $user->setEmail('test@example.com');
      $user->setFirstName('Test');
      $user->setLastName('User');
      $user->setIsAdmin(true);
      $user->setPassword('this-is-correct');
      $user->save();
  }

    public function providerTestLoginInvalidCredentials()
    {
        return [
      [ 'test@example.com', 'wrong-password' ],
      [ 'not-an-email', 'this-is-correct' ],
      [ 'another-not-an-email', 'wrong' ],
      [ '', '' ],
      [ 'no-password@email.com', '' ],
    ];
    }

  /**
  * @param string $username
  * @param string $password
  *
  * @dataProvider providerTestLoginInvalidCredentials
  */
  public function testPostLoginInvalidCredentials($username, $password)
  {
      $response = $this->runApp('POST', '/login', ['username' => $username, 'password' => $password]);

      $this->assertEquals(401, $response->getStatusCode());
      $this->assertContains('Username or password incorrect.', (string)$response->getBody());
  }

    public function testPostLoginTooManyAttempts()
    {
        $i = 0;
        while ($i < 15) {
            $response = $this->runApp('POST', '/login', ['username' => 'spam@example.com', 'password' => 'this-is-not-correct']);
            $i += 1;
        }
        $this->assertEquals($i, 15);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertContains('Too many failed login attempts', (string)$response->getBody());
    }



  /**
  * Test that the index route returns a rendered response containing the text 'Dashbpard', 'Totals' and 'view all'
  */
  public function testGetLoginSuccessful()
  {
      $response = $this->runApp('GET', '/login');

      $this->assertEquals(401, $response->getStatusCode());
      $this->assertContains('Login', (string)$response->getBody());
  }

    public function testPostLoginSuccessful()
    {
        $response = $this->runApp('POST', '/login', ['username' => 'test@example.com', 'password' => 'this-is-correct']);

        $this->assertEquals(303, $response->getStatusCode());
        $this->assertTrue(isset($_SESSION['userId']));
    //$this->assertContains('Dashboard', (string)$response->getBody());
    //$this->assertContains('Upcoming Events', (string)$response->getBody());
    //$this->assertNotContains('Hello', (string)$response->getBody());
    }


   /**
   * @depends testPostLoginSuccessful
    * Test user is rediected if there are already logged in.
    */
    public function testGetLoginAfterSuccessfulAuth()
    {
        $response = $this->runApp('POST', '/login', ['username' => 'test@example.com', 'password' => 'this-is-correct']);
        $this->assertTrue(isset($_SESSION['userId']));

      // ensure page now redirects
      $response = $this->runApp('GET', '/login');

        $this->assertEquals(302, $response->getStatusCode());
    }



  /**
  * @depends testPostLoginSuccessful
  * Test that the logout route won't accept a post request
  */
  public function testPostLogoutNotAccepted()
  {
      $response = $this->runApp('POST', '/logout', ['test']);

      $this->assertEquals(302, $response->getStatusCode());
    //$this->assertContains('Method not allowed', (string)$response->getBody());
  }

  /**
  * @depends testPostLoginSuccessful
  * Test that the logout route accepts a get requests
  */
  public function testGetLogoutAccepted()
  {
      $response = $this->runApp('GET', '/logout');

      $this->assertEquals(302, $response->getStatusCode());
      $this->assertFalse(isset($_SESSION['userId']));
  }
}
