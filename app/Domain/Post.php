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
    private DateTime $created;
    private DateTime $updated;

    public function __construct(
        string $uuid,
        AuthorName $author_name,
        Slug $slug,
        string $image,
        string $content,
        int $views,
        DateTime $created,
        DateTime $updated
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

        return new Post($uuid, $author_name, $slug, $image, $content, 0, $now, $now);
    }

    /** @return Array|Post */
    public function getPostWithSlug(string $slug)
    {
        try {
            $slug = new Slug($slug);
        } catch (InvalidSlug $error) {
            return ['status' => 'fail', 'data' => ['slug' => $error->getMessage()]
            ];
        }
        $post = $this->getRepository()->getPostWithSlug($slug);
        if (empty($post)) {
            return [
                'status' => 'fail',
                'data' => ['post' => trans('Post saved successfully.')]
            ];
        }
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function getRepository(): PostRepositoryInterface
    {
        return new PostRepositoryIlluminate();
    }

    public function savePost()
    {
        $existing_post_slug = $this->getRepository()->checkSlugExists($this->slug);
        if ($existing_post_slug > 0) {
            return [
                'status' => 'fail',
                'data' => ['slug' => trans('Error, there is already a post with this slug.')]
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

    public function updateAuthor(string $author_name)
    {
        try {
            $author_name = new AuthorName($author_name);
        } catch (InvalidAuthorName $error) {
            return [
                'status' => 'fail',
                'data' => ['authorName' => $error->getMessage()]
            ];
        }
        if ($this->getRepository()->saveAuthorNameUpdate($this->uuid, $author_name)) {
            return [
                'status' => 'success',
                'data' => ['authorName' => trans('Author name updated successfully.')]
            ];
        }
        return [
            'status' => 'fail',
            'data' => ['message' => trans('Error, please try again.')]
        ];
    }
}
