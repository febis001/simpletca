<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * @method self withAllowed($allowed = null)
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 * @method self withSize($size = null)
 * @method self withMM($mM = null)
 * @method self withMMOppositeField($mMOppositeField = null)
 */
class RelationMMShortcut extends AbstractShortcut
{
    public function __construct(
        ?string $identifier = null,
        protected ?string $allowed = null,
        protected ?string $mM = null,
        protected ?string $mMOppositeField = null,
        protected ?int $size = null,
        protected ?int $minitems = null,
        protected ?int $maxitems = null,
    ) {
        parent::__construct($identifier);
    }

    public function withItemsRange(?int $minitems = null, ?int $maxitems = null): static
    {
        $this->unsetAttributes['minitems'] = $minitems === null;
        $this->unsetAttributes['maxitems'] = $maxitems === null;

        $this->minitems = $minitems;
        $this->maxitems = $maxitems;

        return $this;
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'group';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return [
            'allowed',
            'minitems',
            'maxitems',
            'size',
            'MM',
            'MM_opposite_field',
        ];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [
            'size' => 1,
        ];
    }

    #[\Override]
    protected function buildConfig(): array
    {
        $config = parent::buildConfig();
        $config['foreign_table'] = $this->allowed;

        return $config;
    }
}
