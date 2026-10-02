<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * @method self withItems($items = null)
 * @method self withRenderType($renderType = null)
 */
class SelectSingleShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?array $items = null,
        protected ?string $renderType = null,
    ) {
        $this->renderType ??= 'selectSingle';
        parent::__construct($identifier);
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'select';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return [
            'items',
            'renderType',
        ];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [];
    }
}
