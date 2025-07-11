<?php

declare(strict_types=1);

// Try to load Composer autoloader first, fallback to simple autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    // Simple PSR-4 autoloader fallback
    spl_autoload_register(function (string $class): void {
        $prefix = 'CitationGenerator\\';
        $baseDir = __DIR__ . '/src/CitationGenerator/';

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    });
}

use CitationGenerator\Application;

$app = new Application();
$app->run($argv);
