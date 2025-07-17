<?php

declare(strict_types=1);


if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {

    spl_autoload_register(function (string $class): void {
        $prefix = 'CitationGenerator\\';
        $baseDir = __DIR__ . '/src/';

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

use CitationGenerator\Core\Application\ConsoleApplication;

$app = new ConsoleApplication();
$app->run($argv);
