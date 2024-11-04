<?php

namespace Febis\SimpleTca\TcaBuilder\Component;

use TYPO3\CMS\Core\Utility\ArrayUtility;

class Palettes implements ComponentInterface
{
    protected array $palettes = [];

    public function __construct(
        private readonly bool $activateLanguage,
        private readonly bool $activateEnableColumns,
    ) {
        $this->addBasePalettes();
    }

    public function addPalette(array $palette)
    {
        ArrayUtility::mergeRecursiveWithOverrule($this->palettes, $palette);
    }

    private function addBasePalettes()
    {
        if ($this->activateLanguage) {
            $this->palettes = array_merge(
                $this->palettes,
                $this->getLanguagePalettes(),
            );
        }

        if ($this->activateEnableColumns) {
            $this->palettes = array_merge(
                $this->palettes,
                $this->getEnableColumnsPalette(),
            );
        }
    }

    private function getLanguagePalettes()
    {
        return [
            'paletteLanguage' => [
                'showitem' => '
                    sys_language_uid;
                    LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:sys_language_uid_formlabel,
                    l18n_parent
                ',
            ],
        ];
    }

    private function getEnableColumnsPalette()
    {
        return [
            'paletteHidden' => [
                'showitem' => '
                    hidden;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:field.default.hidden
                ',
            ],
            'paletteAccess' => [
                'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.access',
                'showitem' => '
                    starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.starttime_formlabel,
                    endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.endtime_formlabel
                ',
            ],
        ];
    }

    #[\Override]
    public function getArray(): array
    {
        return ['palettes' => $this->palettes];
    }
}
