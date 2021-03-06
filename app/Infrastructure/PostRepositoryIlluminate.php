<?php

declare(strict_types=1);

namespace app\Infrastructure;

use app\Domain\Post;
use app\Domain\PostRepositoryInterface;
use Illuminate\Pagination\Paginator;
use support\Db;

class PostRepositoryIlluminate implements PostRepositoryInterface
{
    public function returnAllPosts(int $limit, int $current_page): Paginator
    {
        Paginator::currentPageResolver(function () use ($current_page) {
            return $current_page;
        });
        return Db::connection('mongodb')->collection('post')->simplePaginate($limit);
    }

    public function checkSlugOrUUIDExists(string $uuid, string $slug): int
    {
        return Db::connection('mongodb')->collection('post')
            ->whereIn('_id', [$uuid, $slug])
            ->orWhereIn('slug', [$uuid, $slug])
            ->count();
    }

    public function getPostBySlugOrUUID(string $slug_or_uuid): ?array
    {
        $post = Db::connection('mongodb')->collection('post')
            ->where('_id', $slug_or_uuid)
            ->orWhere('slug', $slug_or_uuid)
            ->get();
        if ($post->isEmpty()) {
            return null;
        }
        return $post->first();
    }

    public function savePost(Post $post): bool
    {
        return Db::connection('mongodb')->collection('post')->insert([
            '_id' => $post->uuid,
            'author_name' => (string) $post->author_name,
            'slug' => (string) $post->slug,
            'image' => $post->image,
            'content' => $post->content,
            'views' => $post->views,
            'created' => $post->created,
            'updated' => $post->updated
        ]);
    }

    public function updatePost(Post $post): bool
    {
        $update = Db::connection('mongodb')->collection('post')->where('_id', $post->uuid)->update([
            'author_name' => (string) $post->author_name,
            'slug' => (string) $post->slug,
            'image' => $post->image,
            'content' => $post->content,
            'views' => $post->views,
            'updated' => $post->updated
        ], ['upsert' => true]);
        return $update > 0 ? true : false;
    }

    public function deletePostBySlugOrUUID(string $slug_or_uuid): bool
    {
        $deleted = Db::connection('mongodb')->collection('post')
            ->where('_id', $slug_or_uuid)
            ->orWhere('slug', $slug_or_uuid)
            ->delete();
        if ($deleted === 0) {
            return false;
        }
        return true;
    }

    public function increasePostViews(string $uuid): bool
    {
        $increase_view = Db::connection('mongodb')->collection('post')
            ->where('_id', $uuid)
            ->increment('views');
        return $increase_view > 0 ? true : false;
    }
}
