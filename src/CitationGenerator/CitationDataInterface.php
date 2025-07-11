<?php

declare(strict_types=1);

namespace CitationGenerator;

interface CitationDataInterface
{
    public function getTitle(): ?string;
    public function getAuthors(): array;
    public function getSource(): ?string;
    public function getYear(): ?int;
    public function getMonth(): ?int;
    public function getDay(): ?int;
    public function getVolume(): ?string;
    public function getIssue(): ?string;
    public function getFirstPage(): ?string;
    public function getLastPage(): ?string;
    public function getPageRange(): ?string;
    public function getDoi(): ?string;
}
