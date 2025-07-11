<?php

declare(strict_types=1);

namespace CitationGenerator;

final class DoiValidator
{
    private const DOI_PATTERN = '/^10\.\d+\/.+/';

    /**
     * Validate and sanitize DOI format
     */
    public static function validate(string $doi): string
    {
        $doi = strtolower(trim($doi));
        
        if (empty($doi)) {
            throw new InvalidDoiException($doi);
        }

        // Remove common prefixes if present
        $doi = preg_replace('#^(https?://)?(?:dx\.)?doi\.org/#', '', $doi);
        
        // Basic DOI format validation
        if (!preg_match(self::DOI_PATTERN, $doi)) {
            throw new InvalidDoiException($doi);
        }

        return $doi;
    }
}
