<?php

namespace tests\Domain\ValueObjects;

use app\Domain\ValueObjects\AuthorName;
use app\Domain\ValueObjects\Exceptions\InvalidAuthorName;
use PHPUnit\Framework\TestCase;

class AuthorNameTest extends TestCase
{
    public function testAuthorNameCannotBeEmpty()
    {
        $this->expectException(InvalidAuthorName::class);
        $this->expectErrorMessage('The author name cannot be empty.');
        new AuthorName('');
    }
    public function testAuthorNameMustBe3CharactersLong()
    {
        $this->expectException(InvalidAuthorName::class);
        $this->expectErrorMessage('The author name must be at least 3 characters.');
        new AuthorName('hi');
    }

    public function testAuthorCannotHaveAccent()
    {
        $this->expectException(InvalidAuthorName::class);
        $this->expectErrorMessage('The author name cannot have special characters.');
        new AuthorName("O'Donnell,Chris");
        $this->expectException(InvalidAuthorName::class);
        new AuthorName("Andrééé");
        $this->expectException(InvalidAuthorName::class);
        new AuthorName("Antônio");
    }

    public function testAuthorNameCannotHaveSpaceAtTheBegginingAtTheMiddleAndAtTheEnd()
    {
        $this->expectException(InvalidAuthorName::class);
        $this->expectErrorMessage('The author name cannot have special characters.');
        new AuthorName('Wrong Author');
        new AuthorName(' WrongAuthor');
        new AuthorName('Wrong-Author ');
    }

    public function testAuthorNameCannotHaveSpecialCharacters()
    {
        $this->expectException(InvalidAuthorName::class);
        new AuthorName('<h1>Hello WorldÆØÅ!</h1>');
        $this->expectException(InvalidAuthorName::class);
        new AuthorName('<h1>Hello World!</h1>');
    }

    public function testAuthorNameCanBePresentedLikeString()
    {
        $author_name = new AuthorName('author1');
        $this->assertSame('author1', (string) $author_name);
        $author_name = new AuthorName('author2');
        $this->assertSame('author2', (string) $author_name);
        $author_name = new AuthorName('author3');
        $this->assertSame('author3', (string) $author_name);
    }
}
