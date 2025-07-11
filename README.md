# Citation Generator

A PSR-4 compatible PHP library for generating JATS XML citations from DOIs using multiple academic data providers.

## Installation

### Using Composer (Recommended)

```bash
cd citation_generator
composer install
```

### Without Composer

The application includes a fallback PSR-4 autoloader, so it works without Composer.

## Usage

```bash
php doi2jats.php <DOI>
```

Example:
```bash
php doi2jats.php 10.52825/bis.v1i.42
```

## PSR-4 Compliance



## Development

- Lead  and concpet : Dulip Withanage (TIB)
### Testing

```bash
composer test
```

### Adding New Providers

1. Create a new class implementing `CitationProviderInterface`
2. Extend `AbstractCitationProvider` for common functionality
3. Add it to the providers array in `Application`

## Requirements

- PHP 8.0 or higher
- ext-dom (for XML generation)
- ext-json (for API responses)
