<?php

namespace BlogUnit\Domain;

use Blog\Domain\Email;
use Blog\Domain\Password;
use Blog\Domain\Post;
use Blog\Domain\PostBodyNotValidException;
use Blog\Domain\PostTitleNotValidException;
use Blog\Domain\User;

class PostTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider invalidTitles
     */
    public function shouldThrownExceptionIfTitleIsNotValid(string $title)
    {
        $this->expectException(PostTitleNotValidException::class);
        $user = new User(new Email('valid@valid.com'), new Password('valid2'));
        new Post(null, $user, $title, 'Valid body');
    }

    /**
     * @test
     * @dataProvider invalidBodies
     */
    public function shouldThrownExceptionIfBodyIsNotValid(string $body)
    {
        $this->expectException(PostBodyNotValidException::class);
        $user = new User(new Email('valid@valid.com'), new Password('valid2'));
        new Post(null, $user, 'Title valid', $body);
    }

    /**
     * @test
     * @dataProvider validTitleAndBody
     */
    public function shouldReturnPostIfTitleAndBodyIsValid(string $title, string $body)
    {
        $user = new User(new Email('valid@valid.com'), new Password('valid2'));
        $post = new Post(null, $user, $title, $body);
        $this->assertInstanceOf(Post::class, $post);
    }

    public function invalidTitles()
    {
        return [
            [''],
            [str_pad('a',Post::TITLE_LIMIT + 1, 'a')],
            [str_pad('a',Post::TITLE_LIMIT + 5, 'a')]
        ];
    }

    public function invalidBodies()
    {
        return [
            [''],
            [str_pad('',Post::BODY_LIMIT + 1, 'a')],
            [str_pad('',Post::BODY_LIMIT + 25, 'a')]
        ];
    }

    public function validTitleAndBody()
    {
        return [
            ['title valid', 'body valid'],
            [str_pad('a',Post::TITLE_LIMIT, 'a'), str_pad('',Post::BODY_LIMIT, 'a')]
        ];
    }
}
