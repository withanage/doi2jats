<?php

declare(strict_types=1);

namespace CitationGenerator;

class InvalidDoiException extends CitationException
{
    public function __construct(string $doi)
    {
        parent::__construct("Invalid DOI format: {$doi}");
    }
}
