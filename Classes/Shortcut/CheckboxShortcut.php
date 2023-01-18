<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

/**
 * @method self withRenderType($renderType = null)
 */
class CheckboxShortcut extends AbstractShortcut
{
    protected ?string $renderType = null;
    protected static function getType(): string
    {
        return "check";
    }

    protected static function getAllowedProperties(): array
    {
        return ['renderType'];
    }

    protected static function getDefaultProperties(): array
    {
        return [];
    }

    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'TINYINT',
            0
        );
    }

    public function __construct(
        ?string $label = null,
        ?string $renderType = null
    ) {
        $this->renderType = $renderType;
        parent::__construct($label);
    }

    /**
     * @return $this
     */
    public function asToggle()
    {
        $this->renderType = 'checkboxToggle';
        return $this;
    }
}
