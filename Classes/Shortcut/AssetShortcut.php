<?php

namespace Febis\SimpleTca\Shortcut;

use Febis\SimpleTca\Data\Field;
use Febis\SimpleTca\Utility\ErrorUtility;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @method self withMinitems($minitems = null)
 * @method self withMaxitems($maxitems = null)
 * @deprecated will be removed with upcoming versions
 */
class AssetShortcut extends AbstractShortcut
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
        $this->fieldName = $fieldName ?? 'assets';
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

        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() >= 12) {
            unset($customSettingsOverride['type']);
        }

        return ExtensionManagementUtility::getFileFieldTCAConfig(
            $this->fieldName,
            $customSettingsOverride,
            $this->allowedFileExtensions ?? $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'],
        );
    }
}
