<?php

declare(strict_types=1);

namespace CitationGenerator;

final class Application
{
    private CitationService $citationService;

    public function __construct()
    {
        $this->initializeService();
    }

    private function initializeService(): void
    {
        $providers = [
            new CrossrefProvider(),
            new OpenAlexProvider()
        ];

        $xmlBuilder = new JatsXmlBuilder();
        $this->citationService = new CitationService($providers, $xmlBuilder);
    }

    public function run(array $args): void
    {
        if (count($args) < 2) {
            $this->showUsage();
            exit(1);
        }

        $doi = $args[1];

        try {
            $citation = $this->citationService->generateCitation($doi);
            echo $citation;
        } catch (InvalidDoiException | CitationException $e) {
            fwrite(STDERR, "Error: {$e->getMessage()}\n");
            exit(1);
        }
    }

    private function showUsage(): void
    {
        echo "Usage: php doi2jats.php <DOI>\n";
        echo "Example: php doi2jats.php 10.52825/bis.v1i.42\n";
    }
}
