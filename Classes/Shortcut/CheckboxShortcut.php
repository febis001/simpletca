<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * @method self withRenderType($renderType = null)
 */
class CheckboxShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?string $renderType = null,
    ) {
        parent::__construct($identifier);
    }

    public function asToggle(): static
    {
        $this->renderType = 'checkboxToggle';
        return $this;
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'check';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return ['renderType'];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [];
    }
}
