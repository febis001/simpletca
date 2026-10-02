<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

class PassthroughShortcut extends AbstractShortcut
{
    protected ?string $eval = null;

    protected ?string $renderType = null;

    #[\Override]
    protected static function getType(): string
    {
        return 'passthrough';
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
}
