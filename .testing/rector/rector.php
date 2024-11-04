<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;
use Rector\PostRector\Rector\NameImportingPostRector;
use Ssch\TYPO3Rector\CodeQuality\General\ConvertImplicitVariablesToExplicitGlobalsRector;
use Ssch\TYPO3Rector\CodeQuality\General\ExtEmConfRector;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;

return RectorConfig::configure()
    ->withPhpSets()
    ->withPreparedSets(deadCode: true, codeQuality: true, codingStyle: true, privatization: true)
    ->withSets(
        [
            Typo3LevelSetList::UP_TO_TYPO3_11,
        ],
    )
    ->withPHPStanConfigs([Typo3Option::PHPSTAN_FOR_RECTOR_PATH])
    ->withSkip(
        [
            'vendor',
            '.testing',
            'public',
            'bin',
            'Examples',
            'Documentation',
            NameImportingPostRector::class => [
                'ext_localconf.php',
                'ext_tables.php',
                'ClassAliasMap.php',
            ],
            ChangeSwitchToMatchRector::class => [
                'Classes/Configuration/ConfigApi.php'
            ]
        ],
    )
    ->withRules(
        [
            ConvertImplicitVariablesToExplicitGlobalsRector::class,
        ],
    )
    ->withConfiguredRule(
        ExtEmConfRector::class,
        [
            ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => [],
        ],
    );
