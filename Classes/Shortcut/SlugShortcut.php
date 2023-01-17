<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;

/**
 * @method self withSize($size = null)
 * @method self withEval($eval = null)
 */
class SlugShortcut extends AbstractShortcut
{
    protected ?string $size = null;
    protected ?string $eval = null;
    protected static function getType(): string
    {
        return "slug";
    }

    protected static function getAllowedProperties(): array
    {
        return ['size', 'eval'];
    }

    protected static function getDefaultProperties(): array
    {
        return [
            'size' => '80',
            'generatorOptions' => [
                'fields' => [
                    'title'
                ],
                'fieldSeparator' => '/',
                'replacements' => [
                    '/' => '-'
                ],
            ],
            'fallbackCharactor' => '-',
            'eval' => 'uniqueInSite',
            'default' => ''
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
        ?string $size = null,
        ?string $eval = null
    ) {
        $this->size = $size;
        $this->eval = $eval;
        parent::__construct($label);
    }
}
