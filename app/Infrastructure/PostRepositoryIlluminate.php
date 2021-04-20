<?php

declare(strict_types=1);

namespace app\Infrastructure;

use app\Domain\Post;
use app\Domain\PostRepositoryInterface;
use app\Domain\ValueObjects\AuthorName;
use app\Domain\ValueObjects\Slug;
use support\Db;

class PostRepositoryIlluminate implements PostRepositoryInterface
{
    public function checkSlugExists(Slug $slug): int
    {
        return Db::connection('mongodb')->collection('test')->where('slug', (string) $slug)->count();
    }

    public function getPostWithSlug(Slug $slug): ?array
    {
        $post = Db::connection('mongodb')->collection('test')->where('slug', (string) $slug)->get();
        if (empty($post)) {
            return null;
        }
        return $post;
    }

    public function savePost(Post $post): bool
    {
        return Db::connection('mongodb')->collection('test')->insert([
            '_id' => $post->uuid,
            'author_name' => (string) $post->author_name,
            'slug' => (string) $post->slug,
            'image' => $post->image,
            'content' => $post->content,
            'views' => $post->views,
            'created' => $post->created->getTimestamp(),
            'updated' => $post->updated->getTimestamp()
        ]);
    }

    public function saveAuthorNameUpdate(string $uuid, AuthorName $author_name): bool
    {
        $update = Db::connection('mongodb')->collection('test')->where('_id', $uuid)->update([
            'author_name' => (string) $author_name
        ], ['upsert' => true]);
        return $update > 0 ? true : false;
    }
}
