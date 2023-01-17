<?php

namespace Febis\SimpleTca\TcaBuilder;

use Febis\SimpleTca\TcaBuilder\Component\Columns;
use Febis\SimpleTca\TcaBuilder\Component\Control;
use Febis\SimpleTca\TcaBuilder\Component\Palettes;
use Febis\SimpleTca\TcaBuilder\Component\Types;

/**
 * Basic usage:
 * $tcaBuilder = new \Febis\SimpleTca\TcaBuilder\TcaBuilder(
 *   'tx_myext_domain_model_entitiy' OR basename(__FILE__, '.php'),
 *   'ExtensionName'
 * );
 *
 * return $tcaBuilder->getTca();
 *
 * --------------------------------------------------------------
 * Advanced usage:
 * $tcaBuilder->activateLanguage = false;
 * $tcaBuilder->ctrl->hideTable = true;
 * $tcaBuilder->columns->addColumn([
 *   'myfield' => [
 *     'label' => '.myfield',
 *     'config' => [
 *       'type' => 'input'
 *     ]
 *   ]
 * ]);
 * $tcaBuilder->palettes->addPalette([
 *   'paletteCustom' => [
 *     'showitem' => 'myfield',
 *   ]
 * ]);
 * $tcaBuilder->types->defaultTypeFields = 'myfield';
 */
class TcaBuilder
{
    protected string $l10n;
    protected string $l10nExt;
    protected string $l10nGeneral;
    public bool $activateLanguage = true;
    public bool $activateSorting = true;
    public bool $activateEnableColumns = true;
    public Control $ctrl;
    public Columns $columns;
    public Palettes $palettes;
    public Types $types;
    protected string $table;

    public function __construct(string $table, string $extKey)
    {
        $this->table = $table;
        $this->l10n = 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:';
        $this->l10nExt = $this->l10n . $table;
        $this->l10nGeneral = $this->l10n . 'general';
        $this->ctrl = new Control(
            $this->l10nExt,
            $this->activateLanguage,
            $this->activateSorting,
            $this->activateEnableColumns
        );
        $this->columns = new Columns(
            $this->table,
            $this->l10nExt,
            $this->activateLanguage,
            $this->activateEnableColumns
        );
        $this->palettes = new Palettes(
            $this->activateLanguage,
            $this->activateEnableColumns
        );
        $this->types = new Types(
            $this->activateLanguage,
            $this->activateEnableColumns
        );
    }

    public function getTca(): array
    {
        return array_merge(
            $this->ctrl->getArray(),
            $this->columns->getArray(),
            $this->palettes->getArray(),
            $this->types->getArray()
        );
    }
}
