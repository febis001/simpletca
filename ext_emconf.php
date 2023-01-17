<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'SimpleTCA',
    'description' => 'Provides shortcuts and utilities for simple TCA generation',
    'category' => 'be',
    'author' => 'Felix Biskoping',
    'version' => '1.0.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.99.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
