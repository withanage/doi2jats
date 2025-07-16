<?php

declare(strict_types=1);

namespace CitationGenerator\Domain\Citation;

final class CitationData implements CitationDataInterface
{
    public function __construct(
        private ?string $title,
        private array $authors,
        private ?string $source,
        private ?int $year = null,
        private ?int $month = null,
        private ?int $day = null,
        private ?string $volume = null,
        private ?string $issue = null,
        private ?string $firstPage = null,
        private ?string $lastPage = null,
        private ?string $pageRange = null,
        private ?string $doi = null
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            authors: $data['authors'] ?? [],
            source: $data['source'] ?? null,
            year: $data['year'] ?? null,
            month: $data['month'] ?? null,
            day: $data['day'] ?? null,
            volume: $data['volume'] ?? null,
            issue: $data['issue'] ?? null,
            firstPage: $data['first_page'] ?? null,
            lastPage: $data['last_page'] ?? null,
            pageRange: $data['page_range'] ?? null,
            doi: $data['doi'] ?? null
        );
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function getAuthors(): array
    {
        return $this->authors;
    }
    public function getSource(): ?string
    {
        return $this->source;
    }
    public function getYear(): ?int
    {
        return $this->year;
    }
    public function getMonth(): ?int
    {
        return $this->month;
    }
    public function getDay(): ?int
    {
        return $this->day;
    }
    public function getVolume(): ?string
    {
        return $this->volume;
    }
    public function getIssue(): ?string
    {
        return $this->issue;
    }
    public function getFirstPage(): ?string
    {
        return $this->firstPage;
    }
    public function getLastPage(): ?string
    {
        return $this->lastPage;
    }
    public function getPageRange(): ?string
    {
        return $this->pageRange;
    }
    public function getDoi(): ?string
    {
        return $this->doi;
    }
}
