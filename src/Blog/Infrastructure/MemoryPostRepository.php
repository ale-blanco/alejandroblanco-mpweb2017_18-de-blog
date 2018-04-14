<?php

namespace Blog\Infrastructure;

use Blog\Domain\Post;
use Blog\Domain\PostRepository;

class MemoryPostRepository implements PostRepository
{
    private $list = [];

    public function save(Post $post): void
    {
        $this->list[] = $post;
    }

    public function findPostFromTitle(string $title): ?Post
    {
        foreach ($this->list as $post) {
            if ($post->title() === $title) {
                return $post;
            }
        }

        return null;
    }

    public function count(): int
    {
        return count($this->list);
    }
}
