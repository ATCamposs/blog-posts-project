<?php

declare(strict_types=1);

namespace app\Domain\ValueObjects;

use app\Domain\ValueObjects\Exceptions\InvalidAuthorName;

class AuthorName
{
    private string $author_name;

    public function __construct(string $author_name)
    {
        $this->checkAuthorName($author_name);
    }

    private function checkAuthorName(string $author_name): void
    {
        if (empty(trim($author_name))) {
            throw new InvalidAuthorName(trans('The author name cannot be empty.'));
        }
        if (strlen($author_name) < 3) {
            throw new InvalidAuthorName(trans('The author name must be at least 3 characters.'));
        }
        if (strlen($author_name) > 25) {
            throw new InvalidAuthorName(trans('The author name must be less than 26 characters.'));
        }
        if (!preg_match('/^[A-z]+$/m', $author_name)) {
            throw new InvalidAuthorName(trans('The author name cannot have special characters.'));
        }
        $this->author_name = $author_name;
    }

    public function __toString(): string
    {
        return $this->author_name;
    }
}
