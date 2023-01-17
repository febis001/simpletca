<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

/**
 * @method self withItems($items = null)
 */
class SelectSingleShortcut extends AbstractShortcut
{
    protected ?array $items = null;
    protected ?string $renderType = null;
    protected static function getType(): string
    {
        return "select";
    }

    protected static function getAllowedProperties(): array
    {
        return ['items'];
    }

    protected static function getDefaultProperties(): array
    {
        return [];
    }

    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'VARCHAR(255)',
            ''
        );
    }

    public function __construct(
        ?string $label = null,
        ?array $items = null,
        ?string $renderType = null
    ) {
        $this->items = $items;
        $this->renderType = $renderType;
        $this->renderType ??= 'selectSingle';
        parent::__construct($label);
    }
}
