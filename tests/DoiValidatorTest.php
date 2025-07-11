<?php

declare(strict_types=1);

namespace CitationGenerator\Tests;

use CitationGenerator\DoiValidator;
use CitationGenerator\InvalidDoiException;
use PHPUnit\Framework\TestCase;

final class DoiValidatorTest extends TestCase
{
    public function testValidDoi(): void
    {
        $doi = '10.1038/nature12373';
        $result = DoiValidator::validate($doi);
        
        $this->assertEquals($doi, $result);
    }

    public function testInvalidDoiThrowsException(): void
    {
        $this->expectException(InvalidDoiException::class);
        DoiValidator::validate('invalid-doi');
    }

    public function testDoiWithUrlPrefix(): void
    {
        $result = DoiValidator::validate('https://doi.org/10.1038/nature12373');
        $this->assertEquals('10.1038/nature12373', $result);
    }
}
