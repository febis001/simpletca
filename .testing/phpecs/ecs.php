<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/../../Classes',
        __DIR__ . '/../../Configuration',
        __DIR__ . '/../../ext_localconf.php',
        __DIR__ . '/../../ext_tables.php',
    ])
    ->withPreparedSets(
        psr12: true,
        arrays: true,
        controlStructures: true,
        strict: true
    );
