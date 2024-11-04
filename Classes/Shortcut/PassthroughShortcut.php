<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

class PassthroughShortcut extends AbstractShortcut
{
    protected ?string $eval = null;

    protected ?string $renderType = null;

    #[\Override]
    protected static function getType(): string
    {
        return "passthrough";
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return [];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [];
    }

    #[\Override]
    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'VARCHAR(255)',
            '',
        );
    }

    public function __construct(
        ?string $identifier = null,
    ) {
        parent::__construct($identifier);
    }
}
