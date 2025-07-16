<?php

declare(strict_types=1);

namespace CitationGenerator\Infrastructure\Provider;

use CitationGenerator\Core\Exception\ProviderException;

final class OpenAlexProvider extends AbstractCitationProvider
{
    public function __construct()
    {
        parent::__construct('https://api.openalex.org/works');
    }

    public function getCitationData(string $doi): ?array
    {
        try {
            $url = $this->baseUrl . '/https://doi.org/' . urlencode($doi);
            $response = $this->makeRequest($url);

            if (!$response) {
                return null;
            }

            return $this->transformData($response);
        } catch (ProviderException) {
            return null;
        }
    }

    public function getProviderName(): string
    {
        return 'OpenAlex';
    }

    protected function transformData(array $rawData): array
    {
        $authors = [];
        foreach ($rawData['authorships'] ?? [] as $authorship) {
            $displayName = $authorship['author']['display_name'] ?? '';
            $nameParts = explode(' ', $displayName);
            $family = array_pop($nameParts);
            $given = implode(' ', $nameParts);

            $authors[] = [
                'family' => $family,
                'given' => $given
            ];
        }

        $venue = $rawData['host_venue'] ?? [];
        $publicationDate = $this->extractDate($rawData['publication_date'] ?? '');

        return [
            'title' => $rawData['title'] ?? null,
            'authors' => $authors,
            'source' => $venue['display_name'] ?? null,
            'year' => $publicationDate['year'] ?? null,
            'month' => $publicationDate['month'] ?? null,
            'day' => $publicationDate['day'] ?? null,
            'volume' => $venue['volume'] ?? null,
            'issue' => $venue['issue'] ?? null,
            'first_page' => $venue['first_page'] ?? null,
            'last_page' => $venue['last_page'] ?? null,
            'page_range' => $venue['page_range'] ?? null,
            'doi' => $rawData['doi'] ?? null
        ];
    }

    private function extractDate(string $dateString): array
    {
        if (empty($dateString)) {
            return [];
        }

        $parts = explode('-', $dateString);
        return [
            'year' => isset($parts[0]) ? (int)$parts[0] : null,
            'month' => isset($parts[1]) ? (int)$parts[1] : null,
            'day' => isset($parts[2]) ? (int)$parts[2] : null
        ];
    }
}
