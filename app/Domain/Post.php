<?php

declare(strict_types=1);

namespace app\Domain;

use app\Domain\ValueObjects\AuthorName;
use app\Domain\ValueObjects\Exceptions\InvalidAuthorName;
use app\Domain\ValueObjects\Exceptions\InvalidSlug;
use app\Domain\ValueObjects\Slug;
use app\Infrastructure\PostRepositoryIlluminate;
use app\Infrastructure\UUIDGenerator;
use DateTime;

class Post
{
    private string $uuid;
    private AuthorName $author_name;
    private Slug $slug;
    private string $image;
    private string $content;
    private int $views;
    private int $created;
    private int $updated;

    public function __construct(
        string $uuid,
        AuthorName $author_name,
        Slug $slug,
        string $image,
        string $content,
        int $views,
        int $created,
        int $updated
    ) {
        $this->uuid = $uuid;
        $this->author_name = $author_name;
        $this->slug = $slug;
        $this->image = $image;
        $this->content = $content;
        $this->views = $views;
        $this->created = $created;
        $this->updated = $updated;
    }

    /** @return Array|Post */
    public static function createNewPost(string $author_name, string $slug, string $image, string $content)
    {
        $uuid = UUIDGenerator::generate();
        try {
            $author_name = new AuthorName($author_name);
            $slug = new Slug($slug);
        } catch (InvalidAuthorName $error) {
            return ['status' => 'fail', 'data' => ['authorName' => $error->getMessage()]
            ];
        } catch (InvalidSlug $error) {
            return ['status' => 'fail', 'data' => ['slug' => $error->getMessage()]
            ];
        }
        $now = new DateTime();
        $now = $now->getTimestamp();

        return new Post($uuid, $author_name, $slug, $image, $content, 0, $now, $now);
    }

    /** @return Array|Post */
    public static function getPostBySlugOrUUID(string $slug_or_uuid)
    {
        $post = (new PostRepositoryIlluminate())->getPostBySlugOrUUID($slug_or_uuid);
        if ($post === null) {
            return [
                'status' => 'fail',
                'data' => ['message' => trans('The post could not be found.')]
            ];
        }
        if ((new PostRepositoryIlluminate())->increasePostViews($post['_id'])) {
            $post['views']++;
        };
        return new Post(
            $post['_id'],
            new AuthorName($post['author_name']),
            new Slug($post['slug']),
            $post['image'],
            $post['content'],
            $post['views'],
            $post['created'],
            $post['updated']
        );
    }

    public static function delete(string $slug_or_uuid)
    {
        $deleted = (new PostRepositoryIlluminate())->deletePostBySlugOrUUID($slug_or_uuid);
        if (!$deleted) {
            return [
                'status' => 'fail',
                'data' => ['message' => trans('The post could not be found.')]
            ];
        }
        return [
            'status' => 'success',
            'data' => ['message' => trans('Post deleted successfully.')]
        ];
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function getRepository(): PostRepositoryInterface
    {
        return new PostRepositoryIlluminate();
    }

    public function savePost(): array
    {
        $existing_post_slug_or_id = $this->getRepository()->checkSlugOrUUIDExists($this->uuid, (string) $this->slug);
        if ($existing_post_slug_or_id > 0) {
            return [
                'status' => 'fail',
                'data' => ['message' => trans('Error, there is already a post with this slug or unique id.')]
            ];
        }

        if ($this->getRepository()->savePost($this)) {
            return [
                'status' => 'success',
                'data' => ['post' => trans('Post saved successfully.')]
            ];
        }
        return [
            'status' => 'fail',
            'data' => ['message' => trans('Error, please try again.')]
        ];
    }

    public function update($update_properties)
    {
        $updated = false;
        if (
            isset($update_properties['image']) &&
            empty(trim($update_properties['image'])) &&
            isset($update_properties['content']) &&
            empty(trim($update_properties['content']))
        ) {
            return [
                'status' => 'fail',
                'data' => ['post' => trans('You cannot leave the image and content empty at the same time.')]
            ];
        }

        if (isset($update_properties['image'])) {
            if ($this->image != $update_properties['image']) {
                $this->image = $update_properties['image'];
                $updated = true;
            }
        }
        if (isset($update_properties['content'])) {
            if ($this->content != $update_properties['content']) {
                $this->content = $update_properties['content'];
                $updated = true;
            }
        }

        if (isset($update_properties['authorName'])) {
            try {
                $author_name = new AuthorName($update_properties['authorName']);
                if ($this->author_name != $author_name) {
                    $this->author_name = $author_name;
                    $updated = true;
                }
            } catch (InvalidAuthorName $error) {
                return [
                    'status' => 'fail',
                    'data' => ['authorName' => $error->getMessage()]
                ];
            }
        }
        if (isset($update_properties['slug'])) {
            try {
                $slug = new AuthorName($update_properties['slug']);
                if ($this->slug != $slug) {
                    $this->slug = $slug;
                    $updated = true;
                }
            } catch (InvalidSlug $error) {
                return [
                    'status' => 'fail',
                    'data' => ['slug' => $error->getMessage()]
                ];
            }
        }
        if (!$updated) {
            return [
                'status' => 'fail',
                'data' => ['post' => trans('You need to modify at least 1 field to be able to update the post.')]
            ];
        }
        $this->updated = (new DateTime())->getTimestamp();
        if ($this->getRepository()->updatePost($this)) {
            return [
                'status' => 'success',
                'data' => ['post' => trans('Post updated successfully.')]
            ];
        }
        return [
            'status' => 'fail',
            'data' => ['message' => trans('Error, please try again.')]
        ];
    }
}
