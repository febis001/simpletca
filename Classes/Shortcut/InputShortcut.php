<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

/**
 * @method self withEval($eval = null)
 * @method self withRenderType($renderType = null)
 */
class InputShortcut extends AbstractShortcut
{
    protected ?string $eval = null;
    protected ?string $renderType = null;
    protected static function getType(): string
    {
        return "input";
    }

    protected static function getAllowedProperties(): array
    {
        return ['eval', 'renderType'];
    }

    protected static function getDefaultProperties(): array
    {
        return [
            'eval' => 'trim',
        ];
    }

    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'VARCHAR(255)',
            ''
        );
    }

    public function __construct(
        ?string $label = null,
        ?string $eval = null,
        ?string $renderType = null
    ) {
        $this->eval = $eval;
        $this->renderType = $renderType;
        parent::__construct($label);
    }
}
