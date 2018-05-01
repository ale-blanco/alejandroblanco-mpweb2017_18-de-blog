<?php

namespace BlogUnit\Application;

use Blog\Application\CreatePost;
use Blog\Application\CreatePostCommand;
use Blog\Domain\Email;
use Blog\Domain\EventQueue;
use Blog\Domain\Password;
use Blog\Domain\Post;
use Blog\Domain\PostRepository;
use Blog\Domain\User;

class CreatePostTest extends \PHPUnit_Framework_TestCase
{
    protected $repository;
    protected $queue;
    protected $createPost;
    protected $command;

    protected function setUp()
    {
        $this->repository = $this->getMockForAbstractClass(PostRepository::class);
        $this->queue = $this->getMockForAbstractClass(EventQueue::class);
        $this->createPost = new CreatePost($this->repository, $this->queue);
    }

    /** @test */
    public function shouldNotSaveIfPostExist()
    {
        $this->giveMeACommandValid();
        $this->whenPostExist();
        $this->thenNotSaveThePost();
    }

    /** @test */
    public function shouldSaveButNotPublishIfSendPublishFalse()
    {
        $this->giveMeACommandValid();
        $this->whenPostNotExist();
        $this->thenSaveThePostButNotPublish();
    }

    /** @test */
    public function shouldSaveAndPublishIfSendPublishTrue()
    {
        $this->giveMeACommandValid(true);
        $this->whenPostNotExist();
        $this->thenSaveThePostAndPublish();
    }

    private function giveMeACommandValid(bool $publish = false)
    {
        $user = new User(new Email('valid@valid.com'), new Password('valid2'));
        $this->command = new CreatePostCommand('Title', 'Body', $publish, $user);
    }

    private function whenPostExist()
    {
        $this->repository->method('findPostFromTitle')
            ->willReturn(new Post(null, $this->command->user(), 'title', 'body'));
    }

    private function thenNotSaveThePost()
    {
        $this->repository->expects($this->exactly(0))->method('save');
        $this->queue->expects($this->exactly(0))->method('pushEvent');
        $this->createPost->__invoke($this->command);
    }

    private function whenPostNotExist()
    {
        $this->repository->method('findPostFromTitle')->willReturn(null);
    }

    private function thenSaveThePostButNotPublish()
    {
        $this->repository->expects($this->exactly(1))->method('save');
        $this->queue->expects($this->exactly(0))->method('pushEvent');
        $this->createPost->__invoke($this->command);
    }

    private function thenSaveThePostAndPublish()
    {
        $this->repository->expects($this->exactly(1))->method('save');
        $this->queue->expects($this->exactly(1))->method('pushEvent');
        $this->createPost->__invoke($this->command);
    }
}
