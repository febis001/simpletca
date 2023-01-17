<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;
use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 */
class ImageShortcut extends AbstractShortcut
{
    protected ?int $minitems = null;
    protected ?int $maxitems = null;
    protected ?string $fieldName = null;
    protected ?string $allowedFileExtensions = null;
    protected static function getType(): string
    {
        return "inline";
    }

    protected static function getAllowedProperties(): array
    {
        return ['minitems', 'maxitems', 'overrideChildTca', 'appearance'];
    }

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

    protected static function getSqlDefinition(): Field
    {
        return new Field(
            'INT',
            0
        );
    }

    public function __construct(?string $label = null, ?int $minitems = null, ?int $maxitems = null, ?string $fieldName = null, ?string $allowedFileExtensions = null)
    {
        $this->minitems = $minitems;
        $this->maxitems = $maxitems;
        $this->fieldName = $fieldName;
        $this->allowedFileExtensions = $allowedFileExtensions;
        $this->withFieldName($this->fieldName);
        parent::__construct($label);
    }

    /**
     * @return $this
     */
    public function withItemsRange(int $minitems = null, int $maxitems = null)
    {
        $this->unsetAttributes['minitems'] = null === $minitems;
        $this->unsetAttributes['maxitems'] = null === $maxitems;

        $this->minitems = $minitems;
        $this->maxitems = $maxitems;

        return $this;
    }

    /**
     * @return $this
     */
    public function withFieldName($fieldName = null)
    {
        $this->fieldName = $fieldName ?? 'image';
        return $this;
    }

    /**
     * @return $this
     */
    public function withAllowedFileExtension($allowedFileExtensions = null)
    {
        $this->allowedFileExtensions = $allowedFileExtensions;
        return $this;
    }

    protected function buildConfig(): array
    {
        return ExtensionManagementUtility::getFileFieldTCAConfig(
            $this->fieldName,
            parent::buildConfig(),
            $this->allowedFileExtensions ?? $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
        );
    }
}
