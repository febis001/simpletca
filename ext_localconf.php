<?php

declare(strict_types=1);

use Febis\SimpleTca\Hook\TyposcriptLoader;
use TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die();

$cacheConfiguration = [
    'frontend' => PhpFrontend::class,
    'backend' => SimpleFileBackend::class,
    'options' => [
        'defaultLifetime' => 0,
    ],
    'groups' => ['system'],
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['simpletca_tsconfig'] ??= $cacheConfiguration;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['simpletca_typoscript'] ??= $cacheConfiguration;
