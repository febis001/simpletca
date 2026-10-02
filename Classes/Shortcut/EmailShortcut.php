<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * @method self withEval($eval = null)
 * @method self withPlaceholder($placeholder = null)
 * @method self withRequired(bool $required = false)
 */
class EmailShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?string $eval = null,
        protected ?bool $required = null,
    ) {
        parent::__construct($identifier);
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'email';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return [
            'eval',
            'placeholder',
            'required',
        ];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [];
    }
}
