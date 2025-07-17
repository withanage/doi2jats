<?php

declare(strict_types=1);

namespace CitationGenerator\Service;

use CitationGenerator\Core\Exception\CitationException;
use CitationGenerator\Core\Exception\ProviderException;
use CitationGenerator\Domain\Doi\DoiValidator;
use CitationGenerator\Domain\Provider\CitationProviderInterface;
use CitationGenerator\Domain\Provider\XmlBuilderInterface;

final class CitationService
{
    public function __construct(
        private array $providers,
        private XmlBuilderInterface $xmlBuilder
    ) {
    }


    public function generateCitation(string $doi): string
    {
        $validatedDoi = DoiValidator::validate($doi);

        foreach ($this->providers as $provider) {
            try {
                $citationData = $provider->getCitationData($validatedDoi);

                if ($citationData !== null) {
                    return $this->xmlBuilder->buildCitation($citationData);
                }
            } catch (ProviderException $e) {

                error_log("Provider {$provider->getProviderName()} failed: {$e->getMessage()}");

                continue;
            }
        }

        throw new CitationException("No citation data found for DOI: {$validatedDoi}");
    }


    public function addProvider(CitationProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }
}
