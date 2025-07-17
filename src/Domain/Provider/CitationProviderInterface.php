<?php

declare(strict_types=1);

namespace CitationGenerator\Domain\Provider;

interface CitationProviderInterface
{

    public function getCitationData(string $doi): ?array;


    public function getProviderName(): string;
}
