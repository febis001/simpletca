<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * @method self withVersion($version = null)
 * @method self withEnableCopyToClipboard(bool $enableCopyToClipboard = false)
 * @method self withRequired(bool $required = false)
 */
class UuidShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?int $version = null,
        protected ?bool $required = null,
    ) {
        parent::__construct($identifier);
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'uuid';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return [
            'version',
            'enableCopyToClipboard',
            'required',
        ];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [];
    }
}
