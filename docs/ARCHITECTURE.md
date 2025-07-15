# Architecture Documentation

## Namespace Organization

### CitationGenerator\Core\*
Core application concerns including console application and base exceptions.

### CitationGenerator\Domain\*
Domain models and business logic. This layer has no dependencies on infrastructure.

#### Citation
- `CitationData`: Value object representing citation information
- `CitationDataInterface`: Contract for citation data

#### Doi
- `DoiValidator`: DOI validation and sanitization logic

#### Provider
- `CitationProviderInterface`: Contract for citation data providers
- `XmlBuilderInterface`: Contract for XML generation

### CitationGenerator\Infrastructure\*
Infrastructure implementations that fulfill domain contracts.

#### Provider
- `AbstractCitationProvider`: Base class for API providers
- `CrossrefProvider`: Crossref API implementation
- `OpenAlexProvider`: OpenAlex API implementation

#### Xml
- `JatsXmlBuilder`: JATS XML format implementation

### CitationGenerator\Service\*
Application services that coordinate between domain and infrastructure.

- `CitationService`: Main service for generating citations

## Design Patterns

- **Strategy Pattern**: Multiple citation providers
- **Template Method**: Abstract provider base class
- **Dependency Injection**: Service dependencies
- **Value Object**: Citation data
