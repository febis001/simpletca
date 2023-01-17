<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;
use Febis\SimpleTca\Data\Table;
use Febis\SimpleTca\TcaGenerator;

/**
 * @method self withForeignTable($foreignTable = null)
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 */
class IRREShortcut extends AbstractShortcut
{
    protected ?string $foreignTable = null;
    protected ?int $minitems = null;
    protected ?int $maxitems = null;
    protected static function getType(): string
    {
        return "inline";
    }

    protected static function getAllowedProperties(): array
    {
        return ['foreign_table', 'minitems', 'maxitems'];
    }

    protected static function getDefaultProperties(): array
    {
        return [
            'foreign_field' => 'parent',
            'appearance' => [
                'collapseAll' => true,
                'showSynchronizationLink' => true,
                'showAllLocalizationLink' => true,
                'showPossibleLocalizationRecords' => true,
            ],
        ];
    }

    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'INT',
            0
        );
    }

    protected function addFieldForDbGeneration(string $identifier): void
    {
        parent::addFieldForDbGeneration($identifier);
        TcaGenerator::getTcaDefinitionDataInstance()->addTable(
            new Table([
                'parent' => new Field('INT', 0)
            ]),
            $this->foreignTable
        );
    }

    public function __construct(
        ?string $label = null,
        ?string $foreignTable = null,
        ?int $minitems = null,
        ?int $maxitems = null
    ) {
        $this->foreignTable = $foreignTable;
        $this->minitems = $minitems;
        $this->maxitems = $maxitems;
        parent::__construct($label);
    }

    /**
     * @return $this
     */
    public function withItemsRange(int $minitems = null, int $maxitems = null)
    {
        $this->unsetAttributes['minitems'] = null === $minitems;
        $this->unsetAttributes['maxitems'] = null === $maxitems;

        $this->minitems = $minitems;
        $this->maxitems = $maxitems;

        return $this;
    }
}
