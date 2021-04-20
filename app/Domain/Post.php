<?php

declare(strict_types=1);

namespace app\Domain;

use app\Domain\ValueObjects\AuthorName;
use app\Domain\ValueObjects\Slug;
use DateTime;
use Jenssegers\Mongodb\Eloquent\Model;

class Post extends Model
{
    use PostTrait;

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

    public function __get($name)
    {
        return $this->$name;
    }
}
