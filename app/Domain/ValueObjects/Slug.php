<?php

declare(strict_types=1);

namespace app\Domain\ValueObjects;

use app\Domain\ValueObjects\Exceptions\InvalidSlug;

class Slug
{
    private string $slug;

    public function __construct(string $slug)
    {
        $this->checkSlug($slug);
    }

    private function checkSlug(string $slug): void
    {
        if (empty(trim($slug))) {
            throw new InvalidSlug(trans('The slug cannot be empty.'));
        }
        if (strlen($slug) < 8) {
            throw new InvalidSlug(trans('The slug must be at least 8 characters.'));
        }
        if (!preg_match('/([-_]*[a-zA-Z0-9]+([-_]*[a-zA-Z0-9]+)*)/m', $slug)) {
            throw new InvalidSlug(trans('The slug is not in the correct format.'));
        }
        $this->slug = $slug;
    }

    public function __toString(): string
    {
        return $this->slug;
    }
}
