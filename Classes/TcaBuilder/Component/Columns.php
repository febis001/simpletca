<?php

namespace Febis\SimpleTca\TcaBuilder\Component;

use Febis\SimpleTca\Exception\InvalidKeyException;
use Febis\SimpleTca\Shortcut\AbstractShortcut;
use Febis\SimpleTca\TcaGenerator;

class Columns implements ComponentInterface
{
    public ?array $overrideDefaultColumns = null;

    protected array $columns = [];

    public function __construct(
        private readonly string $table,
        private readonly bool $activateLanguage,
        private readonly bool $activateEnableColumns,
    ) {
        $this->addBaseColumns();
    }

    /**
     * @throws InvalidKeyException
     */
    public function addColumn(AbstractShortcut | array $column, string | int $key = null): void
    {
        if (false === is_string($key)) {
            if ($column instanceof AbstractShortcut) {
                $key = $column->getIdentifier();
            } elseif (isset($column['_identifier'])) {
                $key = $column['_identifier'];
            } else {
                throw new InvalidKeyException('Missing key for TCA column', 1724323278);
            }
        }

        $this->columns[$key] = $column;
    }

    /**
     * @throws InvalidKeyException
     * @param list<AbstractShortcut|array> $columns
     */
    public function addColumns(array $columns): void
    {
        foreach ($columns as $key => $column) {
            $this->addColumn($column, $key);
        }
    }

    private function addBaseColumns()
    {
        if ($this->activateEnableColumns) {
            $this->columns = array_merge(
                $this->columns,
                $this->getEnableColumns(),
            );
        }

        if ($this->activateLanguage) {
            $this->columns = array_merge(
                $this->columns,
                $this->getLanguageColumns(),
            );
        }
    }

    private function addDefaultColumns()
    {
        $this->columns = array_merge(
            $this->columns,
            $this->overrideDefaultColumns ?? $this->getDefaultColumns(),
        );
    }

    private function getEnableColumns(): array
    {
        return [
            'hidden' => [
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
                'config' => [
                    'type' => 'check',
                    'renderType' => 'checkboxToggle',
                    'default' => 0,
                    'items' => [
                        [
                            0 => '',
                            'invertStateDisplay' => true,
                        ],
                    ],
                ],
            ],
            'starttime' => [
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
                'config' => [
                    'type' => 'input',
                    'renderType' => 'inputDateTime',
                    'eval' => 'datetime,int',
                    'default' => 0,
                ],
                'l10n_mode' => 'exclude',
                'l10n_display' => 'defaultAsReadonly',
            ],
            'endtime' => [
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
                'config' => [
                    'type' => 'input',
                    'renderType' => 'inputDateTime',
                    'eval' => 'datetime,int',
                    'default' => 0,
                    'range' => [
                        'upper' => mktime(0, 0, 0, 1, 1, 2038),
                    ],
                ],
                'l10n_mode' => 'exclude',
                'l10n_display' => 'defaultAsReadonly',
            ],
        ];
    }

    private function getLanguageColumns(): array
    {
        return [
            'sys_language_uid' => [
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'foreign_table' => 'sys_language',
                    'foreign_table_where' => 'ORDER BY sys_language.title',
                    'items' => [
                        [
                            'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                            -1,
                        ],
                        [
                            'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value',
                            0,
                        ],
                    ],
                    'allowNonIdValues' => true,
                ],
            ],
            'l10n_parent' => [
                'exclude' => true,
                'displayCond' => 'FIELD:sys_language_uid:>:0',
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        [
                            '',
                            0,
                        ],
                    ],
                    'foreign_table' => $this->table,
                    'foreign_table_where' => 'AND ' . $this->table . '.pid=###CURRENT_PID###
                            AND ' . $this->table . '.sys_language_uid IN (-1,0)',
                    'default' => 0,
                ],
            ],
            'l10n_diffsource' => [
                'config' => [
                    'type' => 'passthrough',
                ],
            ],
        ];
    }

    private function getDefaultColumns(): array
    {
        return [
            'title' => TcaGenerator::createInput('title')->withEval('required,trim'),
        ];
    }

    private function completeLabelPaths()
    {
        foreach ($this->columns as $key => $column) {
            if ($column instanceof AbstractShortcut) {
                // no need to translate label because already done at this point
                continue;
            }

            if (!isset($column['label'])) {
                continue;
            }

            if (str_contains((string) $column['label'], 'LLL:')) {
                continue;
            }

            $this->columns[$key]['label'] = TcaGenerator::translate($this->columns[$key]['label']);
        }
    }

    #[\Override]
    public function getArray(): array
    {
        $this->addDefaultColumns();

        $this->completeLabelPaths();
        return ['columns' => $this->columns];
    }
}
