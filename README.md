# Citation Generator

CLI  for generating JATS XML citations from DOIs using multiple academic data providers.

## Installation


### Using Composer (Recommended)

```bash
-git clone https://github.com/withanage/doi2jats/
-cd doi2jats
-composer install
```

### Without Composer

The application includes a fallback PSR-4 autoloader, so it works without Composer.

## Usage

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

# Combined format (all citations in ref-list)
php doi2jats.php -f combined 10.30430/gjae.2023.0350 10.52825/bis.v1i.42

# Bibliography format with numbering
php doi2jats.php --format bibliography 10.30430/gjae.2023.0350 10.52825/bis.v1i.42

# Verbose + combined format
php doi2jats.php -v -f combined 10.30430/gjae.2023.0350 10.52825/bis.v1i.42
```

### Command-Line Options

| Option | Description |
|--------|-------------|
| `-v, --verbose` | Show detailed processing information |
| `-f, --format FORMAT` | Output format: `individual`, `combined`, `bibliography` |
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

#### Combined
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

#### Bibliography
Full bibliography format with labels:
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

#
## Development

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
- ext-simplexml (for XML manipulation in combined formats)

# Development


- Lead  and concept : Dulip Withanage (TIB)


# Known isses

- Method  documentation is   used limitedly.
- 
