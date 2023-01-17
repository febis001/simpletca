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
 */
class RelationShortcut extends AbstractShortcut
{
    protected ?string $allowed = null;
    protected ?int $size = null;
    protected ?int $minitems = null;
    protected ?int $maxitems = null;
    protected static function getType(): string
    {
        return "group";
    }

    protected static function getAllowedProperties(): array
    {
        return ['allowed', 'minitems', 'maxitems', 'size'];
    }

    protected static function getDefaultProperties(): array
    {
        return [
            'size' => 1
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
            $this->allowed
        );
    }

    public function __construct(
        ?string $label = null,
        ?string $allowed = null,
        ?int $size = null,
        ?int $minitems = null,
        ?int $maxitems = null
    ) {
        $this->allowed = $allowed;
        $this->size = $size;
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
