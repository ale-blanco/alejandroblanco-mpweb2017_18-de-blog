<?php

namespace Blog\Domain;

class PostBodyNotValidException extends DomainException
{
    public function __construct()
    {
        parent::__construct('The body can not have more than 2000 characters or be empty');
    }
}
