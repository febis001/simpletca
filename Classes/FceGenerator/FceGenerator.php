<?php

namespace Febis\SimpleTca\FceGenerator;

use Febis\SimpleTca\Exception\NoIdentifierException;
use Febis\SimpleTca\FceGenerator\Showitem\Mode;
use Febis\SimpleTca\TcaGenerator;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class FceGenerator
{
    protected string $identifier = '';
    protected string $cTypeLabel = '';
    protected string $icon = '';
    protected array $palettes = [];
    protected array $columns = [];
    protected string $showItem = '';
    protected Mode $showitemMode = Mode::Default;
    protected array $columnsOverrides = [];

    protected final const CONTENT_TABLE = 'tt_content';
    protected final const FIELDS = [
        'rowDescription' => 'rowDescription,',
        'categories' => 'categories,'
    ];
    protected final const PALETTES = [
        'general' => '--palette--;;general,',
        'headers' => '--palette--;;headers,',
        'frames' => '--palette--;;frames,',
        'appearanceLinks' => '--palette--;;appearanceLinks,',
        'hidden' => '--palette--;;hidden,',
        'access' => '--palette--;;access,',
        'language' => '--palette--;;language,',
    ];
    protected final const TABS = [
        'general' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,',
        'appearance' => '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,',
        'access' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,',
        'notes' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,',
        'extended' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,',
        'language' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,',
        'categories' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,'
    ];
    protected final const JOINED = [
        'generalPrepend' => self::TABS['general'] . self::PALETTES['general'] . self::PALETTES['headers'],
        'appearance' => self::TABS['appearance'] . self::PALETTES['frames'] . self::PALETTES['appearanceLinks'],
        'access' => self::TABS['access'] . self::PALETTES['hidden'] . self::PALETTES['access'],
        'notes' => self::TABS['notes'] . self::FIELDS['rowDescription'],
        'language' => self::TABS['language'] . self::PALETTES['language'],
        'categories' => self::TABS['categories'] . self::FIELDS['categories']
    ];

    public function __construct(
        string $identifier = '',
        string $cTypeLabel = '',
        string $icon = '',
        array $palettes = [],
        array $columns = [],
        string $showItem = '',
        Mode $showitemMode = Mode::Default,
        array $columnsOverrides = []
    ) {
    }

    public function withIdentifier(string $identifier = ''): static
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function withCTypeLabel(string $cTypeLabel = ''): static
    {
        $this->cTypeLabel = $cTypeLabel;
        return $this;
    }

    public function withIcon(string $icon = ''): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function withPalettes(array $palettes = []): static
    {
        $this->palettes = $palettes;
        return $this;
    }

    public function withColumns(array $columns = []): static
    {
        $this->columns = $columns;
        return $this;
    }

    public function withShowItem(string $showitem = ''): static
    {
        $this->showItem = $showitem;
        return $this;
    }

    public function withShowitemMode(Mode $showitemMode = Mode::Default): static
    {
        $this->showitemMode = $showitemMode;
        return $this;
    }

    public function withColumnsOverrides(array $columnsOverrides = []): static
    {
        $this->columnsOverrides = $columnsOverrides;
        return $this;
    }

    /**
     * @throws NoIdentifierException
     */
    public function registerFCE(): void
    {
        if ($this->identifier === '') {
            throw new NoIdentifierException();
        }

        ExtensionManagementUtility::addTCAcolumns(static::CONTENT_TABLE, $this->columns);
        ExtensionManagementUtility::addTcaSelectItem(
            static::CONTENT_TABLE,
            'CType',
            [
                false === empty($this->cTypeLabel) ? static::getLocalizedLabel($this->cTypeLabel) : $this->identifier,
                $this->identifier,
                $this->icon
            ]
        );

        $ttContentExtend = [
            static::CONTENT_TABLE => [
                'ctrl' => [
                    'typeicon_classes' => [
                        $this->identifier => $this->icon
                    ]
                ],
                'palettes' => $this->palettes,
                'types' => [
                    $this->identifier => [
                        'showitem' => static::generateShowitem($this->showItem, $this->showitemMode),
                        'columnsOverrides' => $this->columnsOverrides
                    ],
                ],
            ],
        ];
        ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA'], $ttContentExtend);
    }

    protected static function getLocalizedLabel(string $label): string
    {
        return TcaGenerator::getConfig()->ll() . $label;
    }

    protected static function generateShowitem(string $showitem, Mode $showitemMode): string
    {
        return match ($showitemMode) {
            Mode::Default => static::showitemDefault($showitem),
            Mode::DefaultNoHeader => static::showItemDefaultNoHeader($showitem),
            Mode::Override => $showitem
        };
    }

    protected static function showItemDefault(string $showitem): string
    {
        return
            static::JOINED['generalPrepend'] .
            $showitem .
            static::JOINED['appearance'] .
            static::JOINED['language'] .
            static::JOINED['access'] .
            static::JOINED['categories'] .
            static::JOINED['notes'] .
            static::TABS['extended'];
    }

    protected static function showItemDefaultNoHeader(string $showitem): string
    {
        return
            self::TABS['general'] .
            self::PALETTES['general'] .
            $showitem .
            static::JOINED['appearance'] .
            static::JOINED['language'] .
            static::JOINED['access'] .
            static::JOINED['categories'] .
            static::JOINED['notes'] .
            static::TABS['extended'];
    }
}
