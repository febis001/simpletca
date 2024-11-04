<?php

namespace Febis\SimpleTca\TcaBuilder\Component;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Control implements ComponentInterface
{
    public string $label = 'title';

    public string $labelAlt = '';

    public bool $labelAltForce = false;

    public ?string $labelUserFunc = null;

    public bool $hideTable = false;

    public bool $ignorePageTypeRestriction = false;

    public bool $readOnly = false;

    public bool $adminOnly = false;

    public string $icon = '';

    public string $searchFields = 'title';

    public function __construct(
        protected string $title,
        public bool $activateLanguage,
        public bool $activateSorting,
        public bool $activateEnableColumns,
    ) {
    }

    #[\Override]
    public function getArray(): array
    {
        $ctrl = [
            'ctrl' => [
                'title' => $this->title,
                'label' => $this->label,
                'label_alt' => $this->labelAlt,
                'label_alt_force' => $this->labelAltForce,
                'label_userFunc' => $this->labelUserFunc,
                'tstamp' => 'tstamp',
                'crdate' => 'crdate',
                'cruser_id' => 'cruser_id',
                'origUid' => 't3_origuid',
                'transOrigPointerField' => 'l10n_parent',
                'transOrigDiffSourceField' => 'l10n_diffsource',
                'languageField' => 'sys_language_uid',
                'translationSource' => 'l10n_source',
                'sortby' => 'sorting',
                'delete' => 'deleted',
                'hideTable' => $this->hideTable,
                'readOnly' => $this->readOnly,
                'adminOnly' => $this->adminOnly,
                'enablecolumns' => [
                    'disabled' => 'hidden',
                    'starttime' => 'starttime',
                    'endtime' => 'endtime',
                ],
                'typeicon_classes' => [
                    'default' => $this->icon,
                ],
                'searchFields' => $this->searchFields,
            ],
        ];

        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() >= 12) {
            $ctrl['ctrl']['security']['ignorePageTypeRestriction'] = $this->ignorePageTypeRestriction;
        }

        $this->removeDisabledConfiguration($ctrl);

        return $ctrl;
    }

    private function removeDisabledConfiguration(array &$ctrl)
    {
        if (!$this->activateLanguage) {
            unset(
                $ctrl['ctrl']['transOrigPointerField'],
                $ctrl['ctrl']['transOrigDiffSourceField'],
                $ctrl['ctrl']['languageField'],
                $ctrl['ctrl']['translationSource'],
            );
        }

        if (!$this->activateSorting) {
            unset(
                $ctrl['ctrl']['sortby'],
            );
        }

        if (!$this->activateEnableColumns) {
            unset(
                $ctrl['ctrl']['delete'],
                $ctrl['ctrl']['enablecolumns'],
            );
        }
    }
}
