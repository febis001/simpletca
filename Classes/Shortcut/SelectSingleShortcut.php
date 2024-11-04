<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

/**
 * @method self withItems($items = null)
 * @method self withRenderType($renderType = null)
 */
class SelectSingleShortcut extends AbstractShortcut
{
    #[\Override]
    protected static function getType(): string
    {
        return "select";
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return ['items', 'renderType'];
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
        protected ?array $items = null,
        protected ?string $renderType = null,
    ) {
        $this->renderType ??= 'selectSingle';
        parent::__construct($identifier);
    }
}
