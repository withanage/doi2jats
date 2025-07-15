# Citation Generator

Generate JATS XML citations from DOIs using multiple academic databases.

## Installation

```bash
# Clone the repository
git clone <repository-url>
cd citation-generator

# Install dependencies (optional)
composer install
```

## Quick Start

```bash
# Generate citation for a single DOI
php doi2jats.php 10.30430/gjae.2023.0350

# Generate multiple citations with verbose output
php doi2jats.php -v 10.30430/gjae.2023.0350 10.52825/bis.v1i.42

# Generate combined reference list
php doi2jats.php -f combined 10.30430/gjae.2023.0350 10.52825/bis.v1i.42

# Generate bibliography format
php doi2jats.php -f bibliography 10.30430/gjae.2023.0350 10.52825/bis.v1i.42
```

## Features

- Multiple citation providers (Crossref, OpenAlex)
- JATS XML output format
- Batch processing support
- Multiple output formats (individual, combined, bibliography)
- Robust error handling
- Clean, extensible architecture

## Architecture

This project follows Domain-Driven Design principles with clear separation of concerns. See [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) for detailed information.

## Development

```bash
# Run tests
composer test

# Static analysis
composer analyse

# Install development dependencies
composer install --dev
```

## License

MIT License
