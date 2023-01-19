<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

class LinkShortcut extends AbstractShortcut
{
    protected static function getType(): string
    {
        return "input";
    }

    protected static function getAllowedProperties(): array
    {
        return [];
    }

    protected static function getDefaultProperties(): array
    {
        return [
            'eval' => 'trim',
            'softref' => 'typolink',
            'renderType' => 'inputLink'
        ];
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
