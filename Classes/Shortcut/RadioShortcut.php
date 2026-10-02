<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * @method self withItems($items = null)
 */
class RadioShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?array $items = null,
    ) {
        parent::__construct($identifier);
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'radio';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return ['items'];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [];
    }
}
