<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 * @method self withTreeConfig($treeConfig = [])
 */
class CategoryShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?int $minitems = null,
        protected ?int $maxitems = null,
        protected array $treeConfig = [],
    ) {
        parent::__construct($identifier);
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'category';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return [
            'minitems',
            'maxitems',
            'treeConfig',
        ];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [];
    }
}
