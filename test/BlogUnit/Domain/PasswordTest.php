<?php

namespace BlogUnit\Domain;

use Blog\Domain\Password;

class PasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider passwordsNotValid
     */
    public function shouldThrowExceptionIfPasswordIsNotValid(string $password)
    {
        $this->expectException(\Blog\Domain\PasswordNotValidException::class);
        new Password($password);
    }

    /**
     * @test
     * @dataProvider passwordsValid
     */
    public function shouldCreateAPasswordClassIfPasswordIsValid(string $password)
    {
        $pass = new Password($password);
        $this->assertInstanceOf(Password::class, $pass);
    }

    /**
     * @test
     * @dataProvider passwordsValid
     */
    public function shouldNotValidIfIsDiferentPassword(string $password)
    {
        $pass = new Password($password);
        $this->assertFalse($pass->valid('dfkj8483dskdk'));
    }

    /**
     * @test
     * @dataProvider passwordsValid
     */
    public function shouldValidIfIsEqualPassword(string $password)
    {
        $pass = new Password($password);
        $this->assertTrue($pass->valid($password));
    }


    public function passwordsNotValid()
    {
        return [
            ['a'],
            ['a2'],
            ['aaadfe'],
            ['32345566765'],
            ['dsf98d7f98d7f988s9f7sdf87s84d'],
            ['dklsjf389fj3209rjfsldkfj32o9f2jf']
        ];
    }

    public function passwordsValid()
    {
        return [
            ['aa2'],
            ['2aa'],
            ['dlkjf387'],
            ['34dlksfj'],
            ['AAADF45'],
            ['dsa4rewr'],
            ['389247329f'],
            ['d7erye64tr5etr6ye7rut8443u76']
        ];
    }
}
