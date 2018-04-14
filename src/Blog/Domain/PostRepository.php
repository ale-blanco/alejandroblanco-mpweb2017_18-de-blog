<?php

namespace Blog\Domain;

interface PostRepository
{
    public function save(Post $post): void;
    public function findPostFromTitle(string $title): ?Post;
}
