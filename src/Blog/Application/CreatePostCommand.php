<?php

namespace Blog\Application;

use Blog\Domain\User;

class CreatePostCommand
{
    private $title;
    private $body;
    private $publish;
    private $user;

    public function __construct(string $title, string $body, bool $publish, User $user)
    {
        $this->title = $title;
        $this->body = $body;
        $this->publish = $publish;
        $this->user = $user;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function publish(): bool
    {
        return $this->publish;
    }

    public function user(): User
    {
        return $this->user;
    }
}
