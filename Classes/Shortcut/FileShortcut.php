<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

use TYPO3\CMS\Frontend\DataProcessing\FilesProcessor;

/**
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 */
class FileShortcut extends AbstractShortcut implements DataProcessorInterface
{
    public function __construct(
        ?string $identifier = null,
        protected ?int $minitems = null,
        protected ?int $maxitems = null,
        protected ?string $allowed = null,
        protected ?string $as = null,
    ) {
        parent::__construct($identifier);
    }

    #[\Override]
    public function getDataProcessorType(): string
    {
        return FilesProcessor::class;
    }

    #[\Override]
    public function getDataProcessorConfig(string $fieldName, string $tableName): array
    {
        return [
            'references' => [
                'table' => $tableName,
                'fieldName' => $fieldName,
            ],
            'as' => $this->as ?? $fieldName,
        ];
    }

    #[\Override]
    protected static function getType(): string
    {
        return 'file';
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return [
            'minitems',
            'maxitems',
            'overrideChildTca',
            'allowed',
        ];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [
            'allowed' => 'common-image-types',
        ];
    }
}
