<?php

declare(strict_types=1);

namespace app\Domain;

use app\Domain\ValueObjects\AuthorName;
use app\Domain\Post;
use app\Domain\ValueObjects\Slug;

interface PostRepositoryInterface
{
    public function checkSlugExists(Slug $slug): int;
    public function getPostWithSlug(Slug $slug): ?array;
    public function savePost(Post $post): bool;
    public function saveAuthorNameUpdate(string $uuid, AuthorName $author_name): bool;
}
