<?php

namespace Blog\Domain;

class Email
{
    private $email;

    public function __construct(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new EmailNotValidException();
        }

        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function equal(self $email): bool
    {
        return $this->email === $email->email();
    }
}
