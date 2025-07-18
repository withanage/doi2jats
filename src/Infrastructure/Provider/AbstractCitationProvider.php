<?php

declare(strict_types=1);

namespace CitationGenerator\Infrastructure\Provider;

use CitationGenerator\Core\Exception\ProviderException;
use CitationGenerator\Domain\Provider\CitationProviderInterface;

abstract class AbstractCitationProvider implements CitationProviderInterface
{
    protected string $baseUrl;
    protected array $defaultHeaders;

    public function __construct(string $baseUrl, array $defaultHeaders = [])
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->defaultHeaders = array_merge([
            'User-Agent' => 'Citation Generator/1.0',
        ], $defaultHeaders);
    }


    protected function makeRequest(string $url, array $headers = []): ?array
    {
        $context = stream_context_create([
            'http' => [
                'header' => $this->buildHeaders(array_merge($this->defaultHeaders, $headers)),
                'timeout' => 10,
                'ignore_errors' => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            throw new ProviderException($this->getProviderName(), "Failed to fetch data from {$url}");
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ProviderException($this->getProviderName(), "Invalid JSON response");
        }

        return $data;
    }


    private function buildHeaders(array $headers): string
    {
        $headerLines = [];
        foreach ($headers as $key => $value) {
            $headerLines[] = "{$key}: {$value}";
        }

        return implode("\r\n", $headerLines);
    }


    abstract protected function transformData(array $rawData): array;
}
