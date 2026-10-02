<?php

declare(strict_types=1);

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

    #[\Override]
    public function getArray(): array
    {
        return [
            'palettes' => $this->palettes,
        ];
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
                    hidden;frontend.db.tt_content:hidden
                ',
            ],
            'paletteAccess' => [
                'label' => 'core.form.palettes:access',
                'showitem' => '
                    starttime,
                    endtime
                ',
            ],
        ];
    }
}
