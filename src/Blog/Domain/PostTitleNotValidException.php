<?php

namespace Blog\Domain;

class PostTitleNotValidException extends DomainException
{
    public function __construct()
    {
        parent::__construct('The title can not have more than 50 characters or be empty');
    }
}
