# Citation Generator
CLI  for generating JATS XML citations from DOIs using multiple academic data providers.

![CI/CD Pipeline](https://github.com/withanage/doi2jats/actions/workflows/main.yml/badge.svg?branch=main)



### Basic Usage (Single DOI)

```bash
php doi2jats.php <DOI>
```

Example:
```bash
php doi2jats.php 10.30430/gjae.2023.0350
```

### Multiple DOIs

```bash
php doi2jats.php <DOI1> <DOI2> <DOI3> ...
```

Example:
```bash
php doi2jats.php 10.30430/gjae.2023.0350 10.52825/bis.v1i.42
```

### Advanced Options

```bash
# Verbose output with processing details
php doi2jats.php -v 10.30430/gjae.2023.0350 10.52825/bis.v1i.42

# Ref-List format (all citations in ref-list)
php doi2jats.php -f reflist 10.30430/gjae.2023.0350 10.52825/bis.v1i.42

# Back  format with numbering
php doi2jats.php --format back 10.30430/gjae.2023.0350 10.52825/bis.v1i.42

# Verbose + reflist format
php doi2jats.php -v -f reflist 10.30430/gjae.2023.0350 10.52825/bis.v1i.42
```


## Installation


### Using Composer (Recommended)

```bash
git clone https://github.com/withanage/doi2jats/
cd doi2jats
composer install

```

### Without Composer

The application includes a fallback PSR-4 autoloader, so it works without Composer.

## Usage

### Command-Line Options

| Option | Description |
|--------|-------------|
| `-v, --verbose` | Show detailed processing information |
| `-f, --format FORMAT` | Output format: `individual`, `reflist`, `back` |
| `-h, --help` | Show help message |

### Output Formats

#### Individual (Default)
Each citation as separate XML documents:
```xml
<!-- DOI: 10.30430/gjae.2023.0350 -->
<?xml version="1.0" encoding="UTF-8"?>
<element-citation publication-type="journal">
  ...
</element-citation>

<!-- DOI: 10.52825/bis.v1i.42 -->
<?xml version="1.0" encoding="UTF-8"?>
<element-citation publication-type="journal">
  ...
</element-citation>
```

####  REflist
All citations wrapped in a reference list:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<ref-list>
  <ref id="ref1">
    <element-citation publication-type="journal">
      ...
    </element-citation>
  </ref>
  <ref id="ref2">
    <element-citation publication-type="journal">
      ...
    </element-citation>
  </ref>
</ref-list>
```

#### Back
Full back format with labels:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<back>
  <ref-list>
    <title>References</title>
    <ref id="bib1">
      <label>1.</label>
      <element-citation publication-type="journal">
        ...
      </element-citation>
    </ref>
    <ref id="bib2">
      <label>2.</label>
      <element-citation publication-type="journal">
        ...
      </element-citation>
    </ref>
  </ref-list>
</back>
```

## Error Handling

The application handles various error scenarios:

- Invalid DOI format: Shows specific error for malformed DOIs
- Network failures:  handles API timeouts and connection issues


### Example with Errors

```bash
$ php doi2jats.php -v 10.30430/gjae.2023.0350 invalid-doi 10.52825/bis.v1i.42
Processing 3 DOI(s) in 'individual' format...
Processing DOI 1/3: 10.30430/gjae.2023.0350
Processing DOI 2/3: invalid-doi
  Error: Invalid DOI format: invalid-doi
Processing DOI 3/3: 10.52825/bis.v1i.42

<!-- DOI: 10.30430/gjae.2023.0350 -->
<?xml version="1.0" encoding="UTF-8"?>
<element-citation publication-type="journal">
  ...
</element-citation>

<!-- ERROR for DOI: invalid-doi - Invalid DOI format: invalid-doi -->

<!-- DOI: 10.52825/bis.v1i.42 -->
<?xml version="1.0" encoding="UTF-8"?>
<element-citation publication-type="journal">
  ...
</element-citation>

=== SUMMARY ===
Total DOIs processed: 3
Successful: 2
Failed: 1

Failed DOIs:
  - invalid-doi: Invalid DOI format: invalid-doi
```


## Development
- PKP advanced knowledge  for code reading and writing required.
- Please note that the tool doesn't include method documentation.


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
- ext-dom
- ext-json
- ext-simplexml (for XML manipulation in reflist formats)

## Contribution

- Lead  and concept : Dulip Withanage (TIB) https://www.tib.eu

