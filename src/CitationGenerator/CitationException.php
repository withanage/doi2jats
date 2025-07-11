<?php

declare(strict_types=1);

namespace CitationGenerator;

use Exception;
use Throwable;

class CitationException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
