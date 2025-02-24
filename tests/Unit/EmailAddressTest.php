<?php

namespace Tests\Unit;

use TechWilk\Rota\EmailAddress;

class EmailAddressTest extends BaseTestCase
{
    public function providerTestValidEmails()
    {
        return [
            ['test@example.com'],
            ['no-password@email.com'],
        ];
    }

    /**
     * @param string $email
     *
     * @dataProvider providerTestValidEmails
     */
    public function testValidEmails($email)
    {
        $emailObject = new EmailAddress($email);

        $this->assertEquals((string) $emailObject, $email);
    }

    public function providerTestInvalidEmails()
    {
        return [
            ['not-an-email'],
            ['another-not-an-email'],
            [''],
        ];
    }

    /**
     * @param string $email
     *
     * @expectedException        InvalidArgumentException
     *
     * @dataProvider providerTestInvalidEmails
     */
    public function testInvalidEmails($email)
    {
        $emailObject = new EmailAddress($email);

        //$this->assertNotEqual((string)$emailObject, $email);
    }
}
