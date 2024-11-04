<?php

namespace Febis\SimpleTca\TcaBuilder\Component;

use TYPO3\CMS\Core\Utility\ArrayUtility;

class Types implements ComponentInterface
{
    public string $defaultTypeFields = '';

    protected array $additionalTypes = [];

    public function __construct(
        private readonly bool $activateLanguage,
        private readonly bool $activateEnableColumns,
    ) {
    }

    public function addAdditionalType(array $type)
    {
        ArrayUtility::mergeRecursiveWithOverrule($this->additionalTypes, $type);
    }

    private function getDefaultType(): array
    {
        $defaultType = '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title,' . ($this->defaultTypeFields !== '' ? ($this->defaultTypeFields . ',') : '');

        if ($this->activateLanguage) {
            $defaultType .= $this->getLanguageString();
        }

        if ($this->activateEnableColumns) {
            $defaultType .= $this->getEnableColumnsString();
        }

        return [
            '0' => [
                'showitem' => $defaultType,
            ],
        ];
    }

    private function getLanguageString()
    {
        return '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;paletteLanguage,';
    }

    private function getEnableColumnsString()
    {
        return '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;paletteHidden,
                    --palette--;;paletteAccess,';
    }

    #[\Override]
    public function getArray(): array
    {
        return ['types' => array_merge($this->getDefaultType(), $this->additionalTypes)];
    }
}
