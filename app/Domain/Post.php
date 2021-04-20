<?php

declare(strict_types=1);

namespace app\Domain;

use app\Domain\ValueObjects\AuthorName;
use DateTime;
use Jenssegers\Mongodb\Eloquent\Model;

class Post extends Model
{
    private AuthorName $author_name;
    private string $content;
    private DateTime $created;
    private DateTime $updated;

    public function __construct(AuthorName $author_name, string $content, DateTime $created, DateTime $updated)
    {
        $this->author_name = $author_name;
        $this->content = $content;
        $this->created = $created;
        $this->updated = $updated;
    }
}