<?php

namespace tests\Domain\ValueObjects;

use app\Domain\ValueObjects\Exceptions\InvalidSlug;
use app\Domain\ValueObjects\Slug;
use PHPUnit\Framework\TestCase;

class SlugTest extends TestCase
{
    public function testSlugEmpty()
    {
        $this->expectException(InvalidSlug::class);
        $this->expectErrorMessage('The slug cannot be empty.');
        new Slug('');
    }

    public function testSlugMustBe3CharactersLong()
    {
        $this->expectException(InvalidSlug::class);
        $this->expectErrorMessage('The slug must be at least 8 characters.');
        new Slug('hi');
    }

    public function testSlugCannotHaveAccent()
    {
        $this->expectException(InvalidSlug::class);
        $this->expectErrorMessage('The slug is not in the correct format.');
        new Slug("O'Donnell,Chris");
        $this->expectException(InvalidSlug::class);
        new Slug("Andrééé");
        $this->expectException(InvalidSlug::class);
        new Slug("Antônio");
    }

    public function testSlugCannotHaveSpaceAtTheBegginingAtTheMiddleAndAtTheEnd()
    {
        $this->expectException(InvalidSlug::class);
        $this->expectErrorMessage('The slug is not in the correct format.');
        new Slug('Wrong Slug');
        new Slug(' WrongSlug');
        new Slug('Wrong-slug ');
    }

    public function testSlugCannotHaveSpecialCharacters()
    {
        $this->expectException(InvalidSlug::class);
        new Slug('<h1>Hello WorldÆØÅ!</h1>');
        $this->expectException(InvalidSlug::class);
        new Slug('<h1>Hello World!</h1>');
    }

    public function testSlugCanBePresentedLikeString()
    {
        $slug = new Slug('test-new-slug');
        $this->assertSame('test-new-slug', (string) $slug);
        $slug = new Slug('test-another-slug');
        $this->assertSame('test-another-slug', (string) $slug);
        $slug = new Slug('AnotherValidSlug');
        $this->assertSame('AnotherValidSlug', (string) $slug);
    }
}
