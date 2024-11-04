<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

/**
 * @method self withEval($eval = null)
 * @method self withRenderType($renderType = null)
 * @method self withRequired(bool $required = false)
 */
class InputShortcut extends AbstractShortcut
{
    #[\Override]
    protected static function getType(): string
    {
        return "input";
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return ['eval', 'renderType', 'required'];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [
            'eval' => 'trim',
        ];
    }

    #[\Override]
    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'VARCHAR(255)',
            '',
        );
    }

    public function __construct(
        ?string $identifier = null,
        protected ?string $eval = null,
        protected ?string $renderType = null,
        protected ?bool $required = null,
    ) {
        parent::__construct($identifier);
    }
}
