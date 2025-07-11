<?php

declare(strict_types=1);

namespace CitationGenerator;

final class CitationService
{
    /**
     * @param array<CitationProviderInterface> $providers
     */
    public function __construct(
        private array $providers,
        private XmlBuilderInterface $xmlBuilder
    ) {}

    /**
     * Generate citation XML for given DOI
     */
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
                // Log error but continue to next provider
                error_log("Provider {$provider->getProviderName()} failed: {$e->getMessage()}");
                continue;
            }
        }

        throw new CitationException("No citation data found for DOI: {$validatedDoi}");
    }

    /**
     * Add a provider to the service
     */
    public function addProvider(CitationProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }
}
