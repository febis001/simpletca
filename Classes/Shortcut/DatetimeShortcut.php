<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * @method self withDbType($dbType = null)
 * @method self withDefault($default = null)
 * @method self withRange($range = [])
 * @method self withRequired(bool $required = false)
 */
class DatetimeShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?bool $required = null,
    ) {
        parent::__construct($identifier);
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'datetime';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return [
            'dbType',
            'default',
            'range',
            'required',
        ];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [];
    }
}
