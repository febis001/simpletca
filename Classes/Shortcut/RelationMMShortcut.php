<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;
use Febis\SimpleTca\Data\Table;
use Febis\SimpleTca\TcaGenerator;

/**
 * @method self withAllowed($allowed = null)
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 * @method self withSize($size = null)
 * @method self withMM($mM = null)
 * @method self withMMOppositeField($mMOppositeField = null)
 */
class RelationMMShortcut extends AbstractShortcut
{
    #[\Override]
    protected static function getType(): string
    {
        return "group";
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return ['allowed', 'minitems', 'maxitems', 'size', 'MM', 'MM_opposite_field'];
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

    public function __construct(
        ?string $identifier = null,
        protected ?string $allowed = null,
        protected ?string $mM = null,
        protected ?string $mMOppositeField = null,
        protected ?int $size = null,
        protected ?int $minitems = null,
        protected ?int $maxitems = null,
    ) {
        parent::__construct($identifier);
    }

    #[\Override]
    protected function buildConfig(): array
    {
        $config = parent::buildConfig();
        $config['foreign_table'] = $this->allowed;

        return $config;
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
