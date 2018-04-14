<?php

namespace Blog\Application;

use Blog\Domain\EventQueue;
use Blog\Domain\Post;
use Blog\Domain\PostCreatedEvent;
use Blog\Domain\PostRepository;

class CreatePost
{
    private $repository;
    private $queue;

    public function __construct(PostRepository $repository, EventQueue $queue)
    {
        $this->repository = $repository;
        $this->queue = $queue;
    }

    public function __invoke(CreatePostCommand $command): Post
    {
        $newPost = new Post(null, $command->title(), $command->body(), $command->publish());
        $post = $this->repository->findPostFromTitle($command->title());
        if ($post !== null) {
            return $post;
        }

        $this->repository->save($newPost);
        if ($command->publish()) {
            $this->queue->pushEvent(new PostCreatedEvent($newPost));
        }

        return $newPost;
    }
}
