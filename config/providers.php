<?php

declare(strict_types=1);

return [
    'providers' => [
        'crossref' => [
            'enabled' => true,
            'priority' => 1,
            'timeout' => 10,
        ],
        'openalex' => [
            'enabled' => true,
            'priority' => 2,
            'timeout' => 10,
        ],
    ],
    'xml' => [
        'format_output' => true,
        'encoding' => 'UTF-8',
    ],
];
