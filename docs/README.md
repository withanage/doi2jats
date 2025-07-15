# Citation Generator

A PHP library for generating JATS XML citations from DOIs using multiple academic databases.

## Project Structure

```
citation-generator/
├── config/                    # Configuration files
│   └── providers.php         # Provider configuration
├── docs/                     # Documentation
│   └── README.md
├── src/                      # Source code
│   ├── Core/                 # Core application logic
│   │   ├── Application/      # Console application
│   │   └── Exception/        # Core exceptions
│   ├── Domain/               # Domain models and interfaces
│   │   ├── Citation/         # Citation data models
│   │   ├── Doi/              # DOI validation
│   │   └── Provider/         # Provider interfaces
│   ├── Infrastructure/       # Infrastructure implementations
│   │   ├── Provider/         # API providers (Crossref, OpenAlex)
│   │   └── Xml/              # XML builders
│   └── Service/              # Application services
│       └── CitationService.php
├── tests/                    # Test files
│   ├── Integration/          # Integration tests
│   └── Unit/                 # Unit tests
├── doi2jats.php             # Main entry point
└── composer.json            # Composer configuration
```

## Architecture

This project follows Domain-Driven Design (DDD) principles with clear separation of concerns:

- **Core**: Contains application logic and base exceptions
- **Domain**: Business logic and interfaces (no dependencies on infrastructure)
- **Infrastructure**: Implementations of domain interfaces (HTTP clients, XML builders)
- **Service**: Application services that orchestrate domain and infrastructure

## Usage

```bash
php doi2jats.php 10.30430/gjae.2023.0350
php doi2jats.php -v -f combined 10.30430/gjae.2023.0350 10.52825/bis.v1i.42
```
