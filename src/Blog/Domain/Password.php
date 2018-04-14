<?php

namespace Blog\Domain;

class Password
{
    private $passwordEncoded;

    public function __construct(string $password)
    {
        if (!$this->validatePass($password)) {
            throw new PasswordNotValidException();
        }

        $this->passwordEncoded = password_hash($password, PASSWORD_BCRYPT);
    }

    public function passwordEncoded()
    {
        return $this->passwordEncoded;
    }

    public function valid(string $plainTextPassword): bool
    {
        return password_verify($plainTextPassword, $this->passwordEncoded);
    }

    private function validatePass(string $password): bool
    {
        $length = mb_strlen($password, 'UTF-8');
        if ($length < 3 || $length > 28) {
            return false;
        }

        if (!preg_match('([a-zA-Z][0-9]|[0-9][a-zA-Z])', $password)) {
            return false;
        }

        return true;
    }
}
