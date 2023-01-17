<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

/**
 */
class RteShortcut extends AbstractShortcut
{
    protected static function getType(): string
    {
        return "text";
    }

    protected static function getAllowedProperties(): array
    {
        return [];
    }

    protected static function getDefaultProperties(): array
    {
        return [
            'enableRichtext' => true,
        ];
    }

    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'TEXT',
            ''
        );
    }

    public function __construct(?string $label = null)
    {
        parent::__construct($label);
    }
}
