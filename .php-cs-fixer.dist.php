<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__) // 👈 Tells it to scan the current directory
    ->exclude('vendor'); // 👈 Optional: skip vendor folder

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        // Add more rules if needed
    ])
    ->setFinder($finder);

