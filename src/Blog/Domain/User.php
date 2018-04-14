<?php

namespace Blog\Domain;

class User
{
    private $email;
    private $password;

    public function __construct(Email $email, Password $password)
    {

        $this->email = $email;
        $this->password = $password;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }
}
