<?php

namespace BlogUnit\Domain;

use Blog\Domain\Email;
use Blog\Domain\EmailNotValidException;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider emailsNotValid
     */
    public function shouldThrowExceptionIfEmailIsNotValid(string $email)
    {
        $this->expectException(EmailNotValidException::class);
        new Email($email);
    }

    /**
     * @test
     * @dataProvider emailsValid
     */
    public function shouldCreateEmailClassIfEmailValid(string $email)
    {
        $emailClass = new Email($email);
        $this->assertInstanceOf(Email::class, $emailClass);
    }

    /**
     * @test
     * @dataProvider emailsValid
     */
    public function shouldGiveMeEmailOriginal(string $email)
    {
        $this->assertEquals($email, (new Email($email))->email());
    }

    /**
     * @test
     * @dataProvider emailsValid
     */
    public function shouldFalseIfIsDiferentEmail(string $email)
    {
        $emailClass = new Email($email);
        $this->assertFalse($emailClass->equal(new Email('aaaa@aaaa.com')));
    }

    /**
     * @test
     * @dataProvider emailsValid
     */
    public function shouldTrueIfIsEqualEmail(string $email)
    {
        $emailClass = new Email($email);
        $this->assertTrue($emailClass->equal(new Email($email)));
    }

    public function emailsNotValid()
    {
        return [
            [''],
            ['a@a'],
            ['sdlkfjdlkjdljfl'],
            ['lskdafjlkdsj@dsalkfj']
        ];
    }

    public function emailsValid()
    {
        return [
            ['dddd@ddddd.com'],
            ['ddd444@eeee.es']
        ];
    }
}
