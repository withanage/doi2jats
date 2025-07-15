<?php

declare(strict_types=1);

namespace CitationGenerator\Core\Exception;

class ProviderException extends CitationException
{
    public function __construct(string $provider, string $message)
    {
        parent::__construct("Provider '{$provider}' error: {$message}");
    }
}
