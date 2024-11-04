<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

/**
 * @method self withAllowedTypes($allowedTypes = null)
 */
class LinkShortcut extends AbstractShortcut
{
    #[\Override]
    protected static function getType(): string
    {
        return "link";
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return ['allowedTypes'];
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
