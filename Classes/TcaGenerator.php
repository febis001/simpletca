<?php

namespace Febis\SimpleTca;

use Febis\SimpleTca\Data\Config;
use Febis\SimpleTca\FceGenerator\FceGenerator;
use Febis\SimpleTca\Shortcut\CheckboxShortcut;
use Febis\SimpleTca\TcaBuilder\TcaBuilder;
use Febis\SimpleTca\Data\TcaDefinitionData;
use Febis\SimpleTca\Exception\MethodNotDefinedException;
use Febis\SimpleTca\Exception\ShortcutNotAllowedException;
use Febis\SimpleTca\Exception\TableNotParsedException;
use Febis\SimpleTca\Shortcut\ImageShortcut;
use Febis\SimpleTca\Shortcut\InputShortcut;
use Febis\SimpleTca\Shortcut\IRREShortcut;
use Febis\SimpleTca\Shortcut\RelationMMShortcut;
use Febis\SimpleTca\Shortcut\RelationShortcut;
use Febis\SimpleTca\Shortcut\RteShortcut;
use Febis\SimpleTca\Shortcut\SelectSingleShortcut;
use Febis\SimpleTca\Shortcut\SlugShortcut;
use Febis\SimpleTca\Shortcut\TcaShortcutInterface;
use Febis\SimpleTca\FceGenerator\Showitem\Mode;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @method static CheckboxShortcut createCheckbox($label = null, $renderType = null)
 * @method static ImageShortcut createImage($label = null, $minitems = null, $maxitems = null, $fieldname = null)
 * @method static IRREShortcut createIRRE($label = null, $foreignTable = null, $minitems = null, $maxitems = null)
 * @method static InputShortcut createInput($label = null, $eval = null, $renderType = null)
 * @method static RelationShortcut createRelation($label = null, $allowed = null, $size = null, $minitems = null, $maxitems = null)
 * @method static RelationMMShortcut createRelationMM($label = null, $allowed = null, $mM = null, $mMOppositeField = null, $size = null, $minitems = null, $maxitems = null)
 * @method static RteShortcut createRte($label = null)
 * @method static SelectSingleShortcut createSelectSingle($label = null, $items = null, $renderType = null)
 * @method static SlugShortcut createSlug($label = null, $size = null, $eval = null)
 */
class TcaGenerator
{
    protected static string $tablename = '';
    protected static bool $parseTablename = true;
    protected static ?TcaDefinitionData $tcaDefinitionDataInstance = null;
    protected static ?Config $config = null;

    private function __construct()
    {
    }

    public static function createTca(string $table, string $extKey): TcaBuilder
    {
        return new TcaBuilder($table, $extKey);
    }

    /**
     * @param string $identifier
     * @param string $cTypeLabel
     * @param string $icon
     * @param array $palettes
     * @param array $columns
     * @param string $showItem
     * @param Mode $showitemMode
     * @param array $columnsOverrides
     * @return FceGenerator
     */
    public static function createFCE(
        string $identifier,
        string $cTypeLabel = '',
        string $icon = '',
        array $palettes = [],
        array $columns = [],
        string $showItem = '',
        Mode $showitemMode = Mode::Default,
        array $columnsOverrides = []
    ): FceGenerator {
        return GeneralUtility::makeInstance(
            FceGenerator::class,
            [
                $identifier,
                $cTypeLabel,
                $icon,
                $palettes,
                $columns,
                $showItem,
                $showitemMode,
                $columnsOverrides
            ]
        );
    }

    public static function getTcaDefinitionDataInstance(): TcaDefinitionData
    {
        if (static::$tcaDefinitionDataInstance === null) {
            static::$tcaDefinitionDataInstance = GeneralUtility::makeInstance(TcaDefinitionData::class);
        }
        return static::$tcaDefinitionDataInstance;
    }

    public static function getConfig(): Config
    {
        if (static::$config === null) {
            static::$config = GeneralUtility::makeInstance(Config::class);
        }
        return static::$config;
    }

    /**
     * Calls Shortcut Methods
     * @param string $name
     * @param array $arguments
     * @return TcaShortcutInterface
     * @throws MethodNotDefinedException
     * @throws ShortcutNotAllowedException
     */
    public static function __callStatic(string $name, array $arguments): TcaShortcutInterface
    {
        preg_match('/\Acreate([A-Z][A-z0-9]+)\z/', $name, $match);

        if (isset($match[0], $match[1])) {
            $fqcn = self::getFQCN($match[1]);
            try {
                $reflectionClass = new \ReflectionClass($fqcn);
                $shortcutInstance = $reflectionClass->newInstance(...$arguments);
                if ($reflectionClass->implementsInterface(TcaShortcutInterface::class)) {
                    if (self::isParseTablename()) {
                        self::parseTablename();
                    }
                    /** @var TcaShortcutInterface $shortcutInstance */
                    return $shortcutInstance;
                }
            } catch (\ReflectionException $exception) {
                throw new ShortcutNotAllowedException(
                    'Shortcut "' . $fqcn . '" was not found or does not implement "' .
                    TcaShortcutInterface::class . '".',
                    1672744035
                );
            }
        }

        throw new MethodNotDefinedException(
            'Method "' . $name . '" is not defined for "' . __CLASS__ . '"',
            1672744033
        );
    }

    protected static function getFQCN($className): string
    {
        return static::getShortcutNamespace() . $className . 'Shortcut';
    }

    protected static function getShortcutNamespace(): string
    {
        return '\\' . __NAMESPACE__ . '\\Shortcut\\';
    }

    /**
     * @return string
     */
    public static function getTablename(): string
    {
        return self::$tablename;
    }

    /**
     * @param string|null $tablename
     */
    public static function setTablename(?string $tablename = null): void
    {
        if ($tablename !== null) {
            self::$tablename = $tablename;
            self::disableParseTablename();
        } else {
            self::$tablename = '';
            self::enableParseTablename();
        }
    }

    /**
     * @return bool
     */
    public static function isParseTablename(): bool
    {
        return self::$parseTablename;
    }

    /**
     * @param bool $parseTablename
     */
    public static function setParseTablename(bool $parseTablename): void
    {
        self::$parseTablename = $parseTablename;
    }


    public static function enableParseTablename(): void
    {
        self::setParseTablename(true);
        self::parseTablename();
    }

    public static function disableParseTablename(): void
    {
        self::setParseTablename(false);
    }

    /**
     * @throws TableNotParsedException
     */
    protected static function parseTablename(): void
    {
        $stackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);

        foreach ($stackTrace as $traceItem) {
            preg_match('/[^\/]+\/Configuration\/TCA\/([^\.]+)\.php/', $traceItem['file'] ?? '', $fileMatch);

            if (!empty($fileMatch) && isset($fileMatch[1])) {
                self::$tablename = $fileMatch[1];
                return;
            }
        }

        throw new TableNotParsedException();
    }
}
