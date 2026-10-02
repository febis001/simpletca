<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * @method self withEval($eval = null)
 * @method self withRenderType($renderType = null)
 * @method self withRequired(bool $required = false)
 */
class InputShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?string $eval = null,
        protected ?string $renderType = null,
        protected ?bool $required = null,
    ) {
        parent::__construct($identifier);
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'input';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return [
            'eval',
            'renderType',
            'required',
        ];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [
            'eval' => 'trim',
        ];
    }
}
