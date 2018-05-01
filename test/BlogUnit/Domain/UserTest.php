<?php

namespace BlogUnit\Domain;

use Blog\Domain\Email;
use Blog\Domain\Password;
use Blog\Domain\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    private $email;
    private $password;
    private $user;
    private $plainPassword;

    /** @test */
    public function shouldGiveMeTheOriginalEmail()
    {
        $this->givenAValidUser();
        $this->thenShouldReturnOriginalEmail();
    }

    /** @test */
    public function shouldGiveMeValidPassword()
    {
        $this->givenAValidUser();
        $this->thenShouldReturnValidPassword();
    }

    private function givenAValidUser()
    {
        $this->plainPassword = 'dfe45dfdfd';
        $this->email = new Email('sssss@dddddd.com');
        $this->password = new Password($this->plainPassword);
        $this->user = new User($this->email, $this->password);
    }

    private function thenShouldReturnOriginalEmail()
    {
        $this->assertTrue($this->email->equal($this->user->email()));
    }

    private function thenShouldReturnValidPassword()
    {
        $this->assertTrue($this->password->valid($this->plainPassword));
    }
}
