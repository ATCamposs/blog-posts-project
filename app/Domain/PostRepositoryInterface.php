<?php

declare(strict_types=1);

namespace app\Domain;

use app\Domain\ValueObjects\AuthorName;
use app\Domain\Post;
use app\Domain\ValueObjects\Slug;

interface PostRepositoryInterface
{
    public function checkSlugOrUUIDExists(string $uuid, string $slug): int;
    public function getPostBySlugOrUUID(string $slug_or_uuid): ?array;
    public function savePost(Post $post): bool;
    public function updatePost(Post $post): bool;
    public function deletePostBySlugOrUUID(string $slug_or_uuid): bool;
    public function increasePostViews(string $uuid): void;
}
