<?php

declare(strict_types=1);

namespace CitationGenerator\Tests\Unit;

use CitationGenerator\Core\Exception\InvalidDoiException;
use CitationGenerator\Domain\Doi\DoiValidator;
use PHPUnit\Framework\TestCase;

final class DoiValidatorTest extends TestCase
{
    public function testValidDoi(): void
    {
        $doi = '10.30430/gjae.2023.0350';
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
        $result = DoiValidator::validate('https://doi.org/10.30430/gjae.2023.0350');
        $this->assertEquals('10.30430/gjae.2023.0350', $result);
    }
}
