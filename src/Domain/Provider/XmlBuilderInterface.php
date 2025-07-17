<?php

declare(strict_types=1);

namespace CitationGenerator\Domain\Provider;

interface XmlBuilderInterface
{

    public function buildCitation(array $citationData): string;
}
