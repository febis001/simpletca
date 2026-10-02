<?php

declare(strict_types=1);

use Febis\SimpleTca\TcaGenerator;

defined('TYPO3') || die();

$identifier = 'example1';

$fce = TcaGenerator::createFCE($identifier)
    ->withCTypeLabel($identifier . '.title')
    ->withIcon('content-header');

// columns can be added either with withColumns(), to override columns, addColumns(), to add multiple columns or
// addColumn() to add a single column

// each of these column configurations creates the same TCA configuration under the hood
$fce->addColumns(
    [
        TcaGenerator::createInput('input'), // preferred way
        'input_key' => TcaGenerator::createInput('input_key'),

        TcaGenerator::createInput('input_build')->build(),
        'input_build_key' => TcaGenerator::createInput('input_build_key')->build(),

        // make sure to add ext_tables.sql statements if you want to use plain TCA configured columns!
        [
            '_identifier' => 'input_default_tca', // added by shortcuts to avoid the need of duplicated identifiers
            'label' => 'LLL:EXT:simpletca/Resources/Private/Language/contentelements.xlf:tt_content.input_default_tca',
            'exclude' => true,
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
        'input_key_default_tca' => [
            'label' => 'LLL:EXT:simpletca/Resources/Private/Language/contentelements.xlf:tt_content.input_key_default_tca',
            'exclude' => true,
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
    ],
);

// different shortcut types
$fce->addColumns(
    [
        TcaGenerator::createText('text', true),
        TcaGenerator::createLink('link'),
        TcaGenerator::createSlug('slug'),

        TcaGenerator::createCheckbox('check')->withRenderType('checkboxToggle'),
        TcaGenerator::createSelectSingle('select')->withItems(
            [
                [
                    'label' => '1',
                    'value' => 1,
                ],
                [
                    'label' => '2',
                    'value' => 2,
                ],
                [
                    'label' => '3',
                    'value' => 3,
                ],
            ],
        ),

        TcaGenerator::createImage('image1'),
        TcaGenerator::createAsset('asset'),
        TcaGenerator::createFile('file'),

        TcaGenerator::createIRRE('irre')->withForeignTable('tx_simpletca_example_irre'),
        TcaGenerator::createRelation('relation')->withAllowed('tx_simpletca_example_relation'),
        TcaGenerator::createRelationMM('relation_mm')
            ->withAllowed('tx_simpletca_example_relationmm')
            ->withMM('tx_simpletca_example_relationmm_mm'),
    ],
);

$fce
    ->withShowItem(
        'input',
        'input_key',
        'input_build',
        'input_build_key',
        'input_default_tca',
        'input_key_default_tca',
        'text',
        'link',
        'slug',
        'check',
        'select',
        'image1',
        'asset',
        'file',
        'irre',
        'relation',
        'relation_mm',
    )
    ->registerFCE(); // use instead ->debugRegisteringFCE() if you want to see what's going on in registerFCE process
