<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Utility\ErrorUtility;

/**
 * @method self withRequired(bool $required = false)
 * @deprecated will be removed with upcoming versions
 */
class RteShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?bool $required = null,
    ) {
        ErrorUtility::triggerDeprecated(self::class, TextShortcut::class);

        parent::__construct($identifier);
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'text';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return ['required'];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [
            'enableRichtext' => true,
        ];
    }
}
