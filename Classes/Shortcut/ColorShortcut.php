<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * @method self withOpacity(bool $opacity = false)
 * @method self withValuePicker($valuePicker = [])
 * @method self withRequired(bool $required = false)
 */
class ColorShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?bool $required = false,
    ) {
        parent::__construct($identifier);
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'color';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return [
            'opacity',
            'valuePicker',
            'required',
        ];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [];
    }
}
