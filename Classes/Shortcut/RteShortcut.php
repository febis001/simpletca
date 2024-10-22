<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

/**
 * @method self withRequired(bool $required = false)
 */
class RteShortcut extends AbstractShortcut
{
    protected static function getType(): string
    {
        return "text";
    }

    protected static function getAllowedProperties(): array
    {
        return ['required'];
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
            'NULL',
        );
    }

    public function __construct(
        ?string $identifier = null,
        protected ?bool $required = null,
    ) {
        parent::__construct($identifier);
    }
}
