<?php

declare(strict_types=1);

namespace CitationGenerator\Domain\Doi;

use CitationGenerator\Core\Exception\InvalidDoiException;

final class DoiValidator
{
    private const DOI_PATTERN = '/^10\.\d+\/.+/';


    public static function validate(string $doi): string
    {
        $doi = strtolower(trim($doi));

        if (empty($doi)) {
            throw new InvalidDoiException($doi);
        }


        $doi = preg_replace('#^(https?://)?(?:dx\.)?doi\.org/#', '', $doi);


        if (! preg_match(self::DOI_PATTERN, $doi)) {
            throw new InvalidDoiException($doi);
        }

        return $doi;
    }
}
