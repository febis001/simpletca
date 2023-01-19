<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

class PassthroughShortcut extends AbstractShortcut
{
    protected ?string $eval = null;
    protected ?string $renderType = null;
    protected static function getType(): string
    {
        return "passthrough";
    }

    protected static function getAllowedProperties(): array
    {
        return [];
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
        ?string $label = null
    ) {
        parent::__construct($label);
    }
}
