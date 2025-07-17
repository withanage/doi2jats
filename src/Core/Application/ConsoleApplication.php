<?php

declare(strict_types=1);

namespace CitationGenerator\Core\Application;

use CitationGenerator\Core\Exception\CitationException;
use CitationGenerator\Core\Exception\InvalidDoiException;
use CitationGenerator\Infrastructure\Provider\CrossrefProvider;
use CitationGenerator\Infrastructure\Provider\OpenAlexProvider;
use CitationGenerator\Infrastructure\Xml\JatsXmlBuilder;
use CitationGenerator\Service\CitationService;

final class ConsoleApplication
{
    private CitationService $citationService;
    private bool $verbose = false;
    private string $outputFormat = 'individual';

    public function __construct()
    {
        $this->initializeService();
    }

    private function initializeService(): void
    {
        $providers = [
            new CrossrefProvider(),
            new OpenAlexProvider(),
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


        $options = $this->parseArguments($args);
        $dois = $options['dois'];

        if (empty($dois)) {
            $this->showUsage();
            exit(1);
        }

        $this->verbose = $options['verbose'];
        $this->outputFormat = $options['format'];

        if ($this->verbose) {
            fwrite(STDERR, "Processing " . count($dois) . " DOI(s) in '{$this->outputFormat}' format...\n");
        }

        try {
            $this->processDois($dois);
        } catch (InvalidDoiException|CitationException $e) {
            fwrite(STDERR, "Error: {$e->getMessage()}\n");
            exit(1);
        }
    }

    private function showUsage(): void
    {
        echo "Citation Generator - Generate JATS XML citations from DOIs\n\n";
        echo "Usage:\n";
        echo "  php doi2jats.php [OPTIONS] <DOI1> [DOI2] [DOI3] ...\n\n";
        echo "Options:\n";
        echo "  -v, --verbose          Show detailed processing information\n";
        echo "  -f, --format FORMAT    Output format: individual, reflist, back\n";
        echo "  -h, --help             Show this help message\n\n";
        echo "Output Formats:\n";
        echo "  individual    Each citation as separate XML (default)\n";
        echo "  reflist      All citations in a <ref-list> wrapper\n";
        echo "  back  Full back format with labels\n\n";
        echo "Examples:\n";
        echo "  php doi2jats.php 10.30430/gjae.2023.0350\n";
        echo "  php doi2jats.php 10.30430/gjae.2023.0350 10.52825/bis.v1i.42\n";
        echo "  php doi2jats.php -v -f reflist 10.30430/gjae.2023.0350 10.52825/bis.v1i.42\n";
        echo "  php doi2jats.php --format back 10.30430/gjae.2023.0350 10.52825/bis.v1i.42\n\n";
    }





    private function parseArguments(array $args): array
    {
        $dois = [];
        $verbose = false;
        $format = 'individual';

        for ($i = 1; $i < count($args); $i++) {
            $arg = $args[$i];

            switch ($arg) {
                case '-v':
                case '--verbose':
                    $verbose = true;

                    break;
                case '-f':
                case '--format':
                    if (isset($args[$i + 1])) {
                        $format = $args[++$i];
                        if (! in_array($format, ['individual', 'reflist', 'back'], true)) {
                            fwrite(STDERR, "Invalid format: {$format}. Use: individual, reflist, or back\n");
                            exit(1);
                        }
                    }

                    break;
                case '-h':
                case '--help':
                    $this->showUsage();
                    exit(0);
                default:

                    $dois[] = $arg;

                    break;
            }
        }

        return [
            'dois' => $dois,
            'verbose' => $verbose,
            'format' => $format,
        ];
    }


    private function processDois(array $dois): void
    {
        $citations = [];
        $errors = [];

        foreach ($dois as $index => $doi) {
            if ($this->verbose) {
                fwrite(STDERR, "Processing DOI " . ($index + 1) . "/" . count($dois) . ": {$doi}\n");
            }

            try {
                $citation = $this->citationService->generateCitation($doi);
                $citations[] = [
                    'doi' => $doi,
                    'citation' => $citation,
                    'success' => true,
                ];
            } catch (InvalidDoiException|CitationException $e) {
                $errors[] = [
                    'doi' => $doi,
                    'error' => $e->getMessage(),
                    'success' => false,
                ];

                if ($this->verbose) {
                    fwrite(STDERR, "  Error: {$e->getMessage()}\n");
                }
            }
        }

        $this->outputResults($citations, $errors);
    }

    private function outputResults(array $citations, array $errors): void
    {
        switch ($this->outputFormat) {
            case 'individual':
                $this->outputIndividual($citations, $errors);

                break;
            case 'reflist':
                $this->outputCombined($citations, $errors);

                break;
            case 'back':
                $this->outputBibliography($citations, $errors);

                break;
        }


        if ($this->verbose || ! empty($errors)) {
            $this->outputSummary($citations, $errors);
        }
    }



    private function outputIndividual(array $citations, array $errors): void
    {
        foreach ($citations as $result) {
            echo $result['citation'] . "\n";
        }

        foreach ($errors as $error) {
            echo "<!-- ERROR for DOI: {$error['doi']} - {$error['error']} -->\n";
        }
    }


    private function outputCombined(array $citations, array $errors): void
    {
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<ref-list>\n";

        foreach ($citations as $index => $result) {
            echo "  <ref id=\"ref" . ($index + 1) . "\">\n";

            $citationXml = $result['citation'];
            $citationXml = preg_replace('/<\?xml[^>]*\?>/', '', $citationXml);
            $citationXml = trim($citationXml);
            $citationXml = "    " . str_replace("\n", "\n    ", $citationXml);
            echo $citationXml . "\n";

            echo "  </ref>\n";
        }

        foreach ($errors as $error) {
            echo "  <!-- ERROR: {$error['doi']} - {$error['error']} -->\n";
        }

        echo "</ref-list>\n";
    }



    private function outputBibliography(array $citations, array $errors): void
    {
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<back>\n";
        echo "  <ref-list>\n";
        echo "    <title>References</title>\n";

        foreach ($citations as $index => $result) {

            if (! is_array($result) || ! isset($result['doi'], $result['citation'])) {
                echo "    <!-- ERROR: Invalid citation data at index {$index} -->\n";

                continue;
            }

            echo "    <ref id=\"bib" . ($index + 1) . "\">\n";
            echo "      <label>" . ($index + 1) . ".</label>\n";

            $citationXml = $result['citation'];
            $citationXml = preg_replace('/<\?xml[^>]*\?>/', '', $citationXml);
            $citationXml = trim($citationXml);
            $citationXml = "    " . str_replace("\n", "\n    ", $citationXml);
            echo $citationXml . "\n";

            echo "    </ref>\n";
        }

        foreach ($errors as $error) {

            if (! is_array($error) || ! isset($error['doi'], $error['error'])) {
                echo "    <!-- ERROR: Invalid error data structure -->\n";

                continue;
            }

            echo "    <!-- ERROR: {$error['doi']} - {$error['error']} -->\n";
        }

        echo "  </ref-list>\n";
        echo "</back>\n";
    }


    private function outputSummary(array $citations, array $errors): void
    {
        $total = count($citations) + count($errors);
        $successful = count($citations);
        $failed = count($errors);

        fwrite(STDERR, "\n=== SUMMARY ===\n");
        fwrite(STDERR, "Total DOIs processed: {$total}\n");
        fwrite(STDERR, "Successful: {$successful}\n");
        fwrite(STDERR, "Failed: {$failed}\n");

        if (! empty($errors)) {
            fwrite(STDERR, "\nFailed DOIs:\n");
            foreach ($errors as $error) {

                if (is_array($error) && isset($error['doi'], $error['error'])) {
                    fwrite(STDERR, "  - {$error['doi']}: {$error['error']}\n");
                } elseif (is_string($error)) {
                    fwrite(STDERR, "  - {$error}\n");
                } else {
                    fwrite(STDERR, "  - Invalid error format\n");
                }
            }
        }
    }

}
