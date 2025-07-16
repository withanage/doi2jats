<?php

declare(strict_types=1);

namespace CitationGenerator\Infrastructure\Xml;

use CitationGenerator\Domain\Citation\CitationData;
use CitationGenerator\Domain\Citation\CitationDataInterface;
use CitationGenerator\Domain\Provider\XmlBuilderInterface;
use DOMDocument;
use DOMElement;

final class JatsXmlBuilder implements XmlBuilderInterface
{
    private DOMDocument $document;

    public function __construct()
    {
        $this->document = new DOMDocument('1.0', 'UTF-8');
        $this->document->formatOutput = true;
    }

    public function buildCitation(array $citationData): string
    {
        $citation = CitationData::fromArray($citationData);

        $elementCitation = $this->document->createElement('element-citation');
        $elementCitation->setAttribute('publication-type', 'journal');
        $this->document->appendChild($elementCitation);

        $this->addAuthors($elementCitation, $citation->getAuthors());
        $this->addElement($elementCitation, 'article-title', $citation->getTitle());
        $this->addElement($elementCitation, 'source', $citation->getSource());
        $this->addDateElements($elementCitation, $citation);
        $this->addElement($elementCitation, 'volume', $citation->getVolume());
        $this->addElement($elementCitation, 'issue', $citation->getIssue());
        $this->addPageElements($elementCitation, $citation);
        $this->addDoiElement($elementCitation, $citation->getDoi());

        return $this->document->saveXML() ?: '';
    }

    private function addAuthors(DOMElement $parent, array $authors): void
    {
        foreach ($authors as $author) {
            $personGroup = $this->document->createElement('person-group');
            $personGroup->setAttribute('person-group-type', 'author');

            $name = $this->document->createElement('name');
            $this->addElement($name, 'surname', $author['family'] ?? '');

            if (! empty($author['given'])) {
                $this->addElement($name, 'given-names', $author['given']);
            }

            $personGroup->appendChild($name);
            $parent->appendChild($personGroup);
        }
    }

    private function addDateElements(DOMElement $parent, CitationDataInterface $citation): void
    {
        $dateElements = [
            'year' => $citation->getYear(),
            'month' => $citation->getMonth(),
            'day' => $citation->getDay(),
        ];

        foreach ($dateElements as $tag => $value) {
            if ($value !== null) {
                $this->addElement($parent, $tag, (string)$value);
            }
        }
    }

    private function addPageElements(DOMElement $parent, CitationDataInterface $citation): void
    {
        $firstPage = $citation->getFirstPage();
        $lastPage = $citation->getLastPage();
        $pageRange = $citation->getPageRange();

        if ($firstPage) {
            $this->addElement($parent, 'fpage', $firstPage);
            if ($lastPage) {
                $this->addElement($parent, 'lpage', $lastPage);
            }
        } elseif ($pageRange) {
            $this->addElement($parent, 'page-range', $pageRange);
        }
    }

    private function addDoiElement(DOMElement $parent, ?string $doi): void
    {
        if ($doi) {
            $pubId = $this->document->createElement('pub-id', htmlspecialchars($doi));
            $pubId->setAttribute('pub-id-type', 'doi');
            $parent->appendChild($pubId);
        }
    }

    private function addElement(DOMElement $parent, string $tagName, ?string $value): void
    {
        if ($value !== null && $value !== '') {
            $element = $this->document->createElement($tagName, htmlspecialchars($value));
            $parent->appendChild($element);
        }
    }
}
