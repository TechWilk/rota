<?php

namespace Tests\Unit;

use TechWilk\Rota\Crypt;

class CryptTest extends BaseTestCase
{
    public function providerTestGenerateToken()
    {
        return [
            [1],
            [2],
            [5],
            [7],
            [9],
            [22],
            [60],
            [120],
            [246],
        ];
    }

    /**
     * @param string $length
     *
     * @dataProvider providerTestGenerateToken
     */
    public function testGenerateToken($length)
    {
        $crypt = new Crypt();
        $token = $crypt->generateToken($length);

        $this->assertEquals(strlen($token), $length);
        $this->assertTrue(is_string($token));
    }

    public function providerTestGenerateIntBetween()
    {
        return [
            [1, 2],
            [1, 100],
            [2, 43],
            [5, 555],
            [7, 9233],
            [5500, 6703],
        ];
    }

    /**
     * @param string $min
     * @param string $max
     *
     * @dataProvider providerTestGenerateIntBetween
     */
    public function testGenerateIntBetween($min, $max)
    {
        $crypt = new Crypt();
        $int = $crypt->generateInt($min, $max);

        $this->assertTrue(is_int($int), 'Returned value is not of type int');
        $this->assertGreaterThanOrEqual($min, $int);
        $this->assertLessThanOrEqual($max, $int);
    }

    public function providerTestGenerateIntInvalidConstraints()
    {
        return [
            [100, 1],
            [2, 2],
        ];
    }

    /**
     * @param string $min
     * @param string $max
     *
     * @expectedException        InvalidArgumentException
     *
     * @dataProvider providerTestGenerateIntInvalidConstraints
     */
    public function testGenerateIntInvalidConstraints($min, $max)
    {
        $crypt = new Crypt();
        $crypt->generateInt($min, $max);
    }
}
