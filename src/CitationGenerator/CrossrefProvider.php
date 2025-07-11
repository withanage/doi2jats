<?php

declare(strict_types=1);

namespace CitationGenerator;

final class CrossrefProvider extends AbstractCitationProvider
{
    public function __construct()
    {
        parent::__construct('https://api.crossref.org/works');
    }

    public function getCitationData(string $doi): ?array
    {
        try {
            $url = $this->baseUrl . '/' . urlencode($doi);
            $response = $this->makeRequest($url);
            
            $message = $response['message'] ?? null;
            if (!$message) {
                return null;
            }

            return $this->transformData($message);
        } catch (ProviderException) {
            return null;
        }
    }

    public function getProviderName(): string
    {
        return 'Crossref';
    }

    protected function transformData(array $rawData): array
    {
        $authors = [];
        foreach ($rawData['author'] ?? [] as $author) {
            $authors[] = [
                'family' => $author['family'] ?? '',
                'given' => $author['given'] ?? ''
            ];
        }

        $publishedDate = $rawData['published-print']['date-parts'][0] ?? 
                        $rawData['published-online']['date-parts'][0] ?? [];

        $pageData = $this->extractPageData($rawData['page'] ?? '');

        return [
            'title' => $rawData['title'][0] ?? null,
            'authors' => $authors,
            'source' => $rawData['container-title'][0] ?? null,
            'year' => $publishedDate[0] ?? null,
            'month' => $publishedDate[1] ?? null,
            'day' => $publishedDate[2] ?? null,
            'volume' => $rawData['volume'] ?? null,
            'issue' => $rawData['issue'] ?? null,
            'first_page' => $pageData['first'] ?? null,
            'last_page' => $pageData['last'] ?? null,
            'doi' => $rawData['DOI'] ?? null
        ];
    }

    private function extractPageData(string $pageString): array
    {
        if (empty($pageString)) {
            return [];
        }

        $pages = explode('-', $pageString);
        return [
            'first' => $pages[0] ?? null,
            'last' => $pages[1] ?? null
        ];
    }
}
