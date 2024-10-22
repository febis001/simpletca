<?php

namespace Febis\SimpleTca\TcaBuilder;

use Febis\SimpleTca\TcaBuilder\Component\Columns;
use Febis\SimpleTca\TcaBuilder\Component\Control;
use Febis\SimpleTca\TcaBuilder\Component\Palettes;
use Febis\SimpleTca\TcaBuilder\Component\Types;
use Febis\SimpleTca\TcaGenerator;

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
    public Control $ctrl;

    public Columns $columns;

    public Palettes $palettes;

    public Types $types;

    public function __construct(
        protected string $table,
        protected bool $activateLanguage = true,
        protected bool $activateSorting = true,
        protected bool $activateEnableColumns = true,
    ) {
        $this->ctrl = new Control(
            TcaGenerator::translate('title'),
            $this->activateLanguage,
            $this->activateSorting,
            $this->activateEnableColumns,
        );
        $this->columns = new Columns(
            $this->table,
            $this->activateLanguage,
            $this->activateEnableColumns,
        );
        $this->palettes = new Palettes(
            $this->activateLanguage,
            $this->activateEnableColumns,
        );
        $this->types = new Types(
            $this->activateLanguage,
            $this->activateEnableColumns,
        );
    }

    public function getTca(): array
    {
        return array_merge(
            $this->ctrl->getArray(),
            $this->columns->getArray(),
            $this->palettes->getArray(),
            $this->types->getArray(),
        );
    }
}
