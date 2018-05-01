<?php

namespace Blog\Domain;

class Post
{
    const TITLE_LIMIT = 50;
    const BODY_LIMIT = 2000;

    private $id;
    private $user;
    private $title;
    private $body;
    private $published;

    public function __construct(?string $id, User $user, string $title, string $body, bool $published = false)
    {
        if ($title == '' || mb_strlen($title, 'UTF-8') > self::TITLE_LIMIT) {
            throw new PostTitleNotValidException();
        }

        if ($body == '' || mb_strlen($body, 'UTF-8') > self::BODY_LIMIT) {
            throw new PostBodyNotValidException();
        }

        $this->id = $id;
        $this->user = $user;
        $this->title = $title;
        $this->body = $body;
        $this->published = $published;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }

    public function published(): bool
    {
        return $this->published;
    }
}
