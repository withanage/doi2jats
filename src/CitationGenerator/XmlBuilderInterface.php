<?php

declare(strict_types=1);

namespace CitationGenerator;

interface XmlBuilderInterface
{
    /**
     * Build XML citation from citation data
     */
    public function buildCitation(array $citationData): string;
}
