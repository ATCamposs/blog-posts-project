<?php

namespace app\Domain\ValueObjects\Exceptions;

use DomainException;

class InvalidSlug extends DomainException
{
    public function __construct(string $exception)
    {
        parent::__construct($exception);
    }
}
