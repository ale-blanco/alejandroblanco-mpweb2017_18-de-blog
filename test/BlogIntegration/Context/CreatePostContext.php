<?php

namespace BlogIntegration\Context;

use Behat\Behat\Context\Context;
use Blog\Application\CreatePost;
use Blog\Application\CreatePostCommand;
use Blog\Domain\Email;
use Blog\Domain\Password;
use Blog\Domain\Post;
use Blog\Domain\PostBodyNotValidException;
use Blog\Domain\PostCreatedEvent;
use Blog\Domain\PostTitleNotValidException;
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

    /**
     * @Given /^a not valid title post (.*)$/
     */
    public function aNotValidTitlePost($title)
    {
        $this->createCommandRepositoryAndQueue($title, false);
    }

    /**
     * @Then /^executing the use case and the post thrown an exception TitleNotValid$/
     */
    public function executingTheUseCaseAndThePostThrownAnExceptionTitleNotValid()
    {
        $resul = $this->createPostWithTry();
        PHPUnit_Framework_Assert::assertTrue($resul instanceof PostTitleNotValidException);
    }

    /**
     * @Given /^a not valid body post (.*)$/
     */
    public function aNotValidBodyPost($body)
    {
        $this->createCommandRepositoryAndQueue('Titulo valido', false, $body);
    }

    /**
     * @Then /^executing the use case and the post thrown an exception BodyNotValid$/
     */
    public function executingTheUseCaseAndThePostThrownAnExceptionBodyNotValid()
    {
        $resul = $this->createPostWithTry();
        PHPUnit_Framework_Assert::assertTrue($resul instanceof PostBodyNotValidException);
    }

    private function createCommandRepositoryAndQueue(string $title, bool $publish, string $body = 'Body')
    {
        $user = new User(new Email('valid@valid.com'), new Password('valid2'));
        $this->command = new CreatePostCommand($title, $body, $publish, $user);
        $this->repository = new MemoryPostRepository();
        $this->queue = new MemoryEventQueue();
    }

    private function createPostWithTry()
    {
        $resul = null;
        $createPost = new CreatePost($this->repository, $this->queue);
        try {
            $this->resul = $createPost->__invoke($this->command);
        } catch (\Exception $ex) {
            $resul = $ex;
        }
        return $resul;
    }
}