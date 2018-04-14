<?php

namespace Blog\Domain;

class DomainException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
