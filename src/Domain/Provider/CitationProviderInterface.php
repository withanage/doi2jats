<?php

declare(strict_types=1);

namespace CitationGenerator\Domain\Provider;

interface CitationProviderInterface
{
    /**
     * Retrieve citation data for a given DOI
     */
    public function getCitationData(string $doi): ?array;

    /**
     * Get the provider name
     */
    public function getProviderName(): string;
}
