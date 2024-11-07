<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;
use Febis\SimpleTca\Utility\ErrorUtility;
use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Frontend\DataProcessing\FilesProcessor;

/**
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 * @deprecated will be removed with upcoming versions
 */
class ImageShortcut extends AbstractShortcut implements DataProcessorInterface
{
    #[\Override]
    protected static function getType(): string
    {
        return "inline";
    }

    #[\Override]
    protected static function getAllowedProperties(): array
    {
        return ['minitems', 'maxitems', 'overrideChildTca', 'appearance'];
    }

    #[\Override]
    protected static function getDefaultProperties(): array
    {
        return [
            'appearance' => [
                'collapseAll' => true,
            ],
            'overrideChildTca' => [
                'types' => [
                    AbstractFile::FILETYPE_IMAGE => [
                        'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette
                        ',
                    ],
                ],
            ],
        ];
    }

    #[\Override]
    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'INT',
            0,
        );
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
                'table' => 'tt_content',
                'fieldName' => $fieldName,
            ],
            'as' => $fieldName,
        ];
    }

    public function __construct(
        ?string $identifier = null,
        protected ?int $minitems = null,
        protected ?int $maxitems = null,
        protected ?string $fieldName = null,
        protected ?string $allowedFileExtensions = null,
    ) {
        ErrorUtility::triggerDeprecated(self::class, FileShortcut::class);

        $this->withFieldName($this->fieldName);
        parent::__construct($identifier);
    }

    public function withItemsRange(int $minitems = null, int $maxitems = null): static
    {
        $this->unsetAttributes['minitems'] = null === $minitems;
        $this->unsetAttributes['maxitems'] = null === $maxitems;

        $this->minitems = $minitems;
        $this->maxitems = $maxitems;

        return $this;
    }

    public function withFieldName($fieldName = null): static
    {
        $this->fieldName = $fieldName ?? 'image';
        return $this;
    }

    public function withAllowedFileExtension($allowedFileExtensions = null): static
    {
        $this->allowedFileExtensions = $allowedFileExtensions;
        return $this;
    }

    #[\Override]
    protected function buildConfig(): array
    {
        $customSettingsOverride = parent::buildConfig();
        unset($customSettingsOverride['type']);

        return ExtensionManagementUtility::getFileFieldTCAConfig(
            $this->fieldName,
            $customSettingsOverride,
            $this->allowedFileExtensions ?? $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
        );
    }
}
