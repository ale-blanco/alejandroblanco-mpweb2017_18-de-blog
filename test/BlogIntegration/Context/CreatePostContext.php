<?php

namespace BlogIntegration\Context;

use Behat\Behat\Context\Context;
use Blog\Application\CreatePost;
use Blog\Application\CreatePostCommand;
use Blog\Domain\Email;
use Blog\Domain\Password;
use Blog\Domain\Post;
use Blog\Domain\PostCreatedEvent;
use Blog\Domain\User;
use Blog\Infrastructure\MemoryEventQueue;
use Blog\Infrastructure\MemoryPostRepository;
use PHPUnit_Framework_Assert;

class CreatePostContext implements Context
{
    protected $command;
    protected $repository;
    protected $queue;
    protected $resul;
    protected $published;

    /**
     * @Given /^a command with exist post title$/
     */
    public function aCommandWithExistPostTitle()
    {
        $title = 'Title';
        $this->createCommandRepositoryAndQueue($title, false);
        $this->repository->save(new Post(null, $this->command->user(), $title, 'Other Body'));
    }

    /**
     * @When /^executing the use case$/
     */
    public function executingTheUseCase()
    {
        $createPost = new CreatePost($this->repository, $this->queue);
        $this->resul = $createPost->__invoke($this->command);
    }

    /**
     * @Then /^the post is not saved$/
     */
    public function thePostIsNotSaved()
    {
        PHPUnit_Framework_Assert::assertEquals($this->repository->count(), 1);
    }

    /**
     * @Given /^a command with not exist post title$/
     */
    public function aCommandWithNotExistPostTitle()
    {
        $this->createCommandRepositoryAndQueue('Title', false);
    }

    /**
     * @Then /^the post is saved$/
     */
    public function thePostIsSaved()
    {
        PHPUnit_Framework_Assert::assertEquals($this->repository->count(), 1);
    }

    /**
     * @Given /^a command with not exist post title and with publish true$/
     */
    public function aCommandWithNotExistPostTitleAndWithPublishTrue()
    {
        $this->createCommandRepositoryAndQueue('Title', true);
        $this->published = false;
        $this->queue->registerHandler(PostCreatedEvent::class, function (PostCreatedEvent $event) {
            $this->published = true;
        });
    }

    /**
     * @Then /^the post is saved and published$/
     */
    public function thePostIsSavedAndPublished()
    {
        PHPUnit_Framework_Assert::assertTrue($this->published);
        $this->thePostIsSaved();
    }

    private function createCommandRepositoryAndQueue(string $title, bool $publish)
    {
        $user = new User(new Email('valid@valid.com'), new Password('valid2'));
        $this->command = new CreatePostCommand($title, 'Body', $publish, $user);
        $this->repository = new MemoryPostRepository();
        $this->queue = new MemoryEventQueue();
    }
}