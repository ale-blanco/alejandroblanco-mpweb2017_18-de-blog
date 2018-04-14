<?php

namespace Blog\Domain;

class PasswordNotValidException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Password not valid. It must be between 3 and 28 characters and contain a minimum of one letter and a number');
    }
}
