<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;
use Febis\SimpleTca\Data\Table;
use Febis\SimpleTca\TcaGenerator;
use TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor;

/**
 * @method self withAllowed($allowed = null)
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 * @method self withSize($size = null)
 */
class RelationShortcut extends AbstractShortcut implements RecursiveDataProcessorInterface
{
    #[\Override]
    protected static function getType(): string
    {
        return "group";
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return ['allowed', 'minitems', 'maxitems', 'size'];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [
            'size' => 1
        ];
    }

    #[\Override]
    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'INT',
            0,
        );
    }

    #[\Override]
    protected function addFieldForDbGeneration(string $identifier): void
    {
        parent::addFieldForDbGeneration($identifier);
        TcaGenerator::getTcaDefinitionDataInstance()->addTable(
            new Table([
                'parent' => new Field('INT', 0)
            ]),
            $this->allowed,
        );
    }

    #[\Override]
    public function getDataProcessorType(): string
    {
        return DatabaseQueryProcessor::class;
    }

    #[\Override]
    public function getDataProcessorConfig(string $fieldName): array
    {
        return [
            'table' => $this->allowed,
            'where.data' => 'field:uid',
            'where.wrap' => 'parent=|',
            'pidInList.field' => 'pid',
            'orderBy' => 'sorting',

            'as' => $fieldName
        ];
    }

    #[\Override]
    public function getTcaTable(): string
    {
        return $this->allowed;
    }

    public function __construct(
        ?string $identifier = null,
        protected ?string $allowed = null,
        protected ?int $size = null,
        protected ?int $minitems = null,
        protected ?int $maxitems = null,
    ) {
        parent::__construct($identifier);
    }

    public function withItemsRange(int $minitems = null, int $maxitems = null): static
    {
        $this->unsetAttributes['minitems'] = null === $minitems;
        $this->unsetAttributes['maxitems'] = null === $maxitems;

        $this->minitems = $minitems;
        $this->maxitems = $maxitems;

        return $this;
    }
}
