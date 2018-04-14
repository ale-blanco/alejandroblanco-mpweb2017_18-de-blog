<?php

namespace Blog\Domain;

class PostCreatedEvent extends GenericEvent
{
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function post(): Post
    {
        return $this->post;
    }
}
