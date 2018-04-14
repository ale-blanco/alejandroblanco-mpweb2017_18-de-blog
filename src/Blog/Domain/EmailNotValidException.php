<?php

namespace Blog\Domain;

class EmailNotValidException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Email not valid');
    }
}
