<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

use TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor;

/**
 * @method self withAllowed($allowed = null)
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 * @method self withSize($size = null)
 */
class RelationShortcut extends AbstractShortcut implements RecursiveDataProcessorInterface
{
    public function __construct(
        ?string $identifier = null,
        protected ?string $allowed = null,
        protected ?int $size = null,
        protected ?int $minitems = null,
        protected ?int $maxitems = null,
    ) {
        parent::__construct($identifier);
    }

    #[\Override]
    public function getDataProcessorType(): string
    {
        return DatabaseQueryProcessor::class;
    }

    #[\Override]
    public function getDataProcessorConfig(string $fieldName, string $tableName): array
    {
        return [
            'table' => $this->allowed,
            'pidInList' => 0,
            'uidInList.field' => $fieldName,
            'orderBy' => 'sorting',

            'as' => $fieldName,
        ];
    }

    #[\Override]
    public function getTcaTable(): string
    {
        return $this->allowed;
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
        ];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [
            'size' => 1,
        ];
    }
}
