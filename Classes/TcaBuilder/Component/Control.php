<?php

namespace Febis\SimpleTca\TcaBuilder\Component;

class Control implements ComponentInterface
{
    public string $label = 'title';
    public bool $hideTable = false;
    public bool $readOnly = false;
    public bool $adminOnly = false;
    public string $icon = '';
    public string $searchFields = 'title';
    protected string $title;
    public bool $activateLanguage;
    public bool $activateSorting;
    public bool $activateEnableColumns;

    public function __construct(string $title, bool $activateLanguage, bool $activateSorting, bool $activateEnableColumns)
    {
        $this->title = $title;
        $this->activateLanguage = $activateLanguage;
        $this->activateSorting = $activateSorting;
        $this->activateEnableColumns = $activateEnableColumns;
    }

    public function getArray(): array
    {
        $ctrl = [
            'ctrl' => [
                'title' => $this->title,
                'label' => $this->label,
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
                $ctrl['ctrl']['translationSource']
            );
        }

        if (!$this->activateSorting) {
            unset(
                $ctrl['ctrl']['sortby']
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
