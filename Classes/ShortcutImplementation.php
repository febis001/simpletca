<?php

declare(strict_types=1);

namespace Febis\SimpleTca;

use Febis\SimpleTca\Exception\CallstackExtractionException;
use Febis\SimpleTca\Exception\MethodNotDefinedException;
use Febis\SimpleTca\Exception\ShortcutNotAllowedException;
use Febis\SimpleTca\Shortcut\AbstractShortcut;
use Febis\SimpleTca\Shortcut\CategoryShortcut;
use Febis\SimpleTca\Shortcut\CheckboxShortcut;
use Febis\SimpleTca\Shortcut\ColorShortcut;
use Febis\SimpleTca\Shortcut\DatetimeShortcut;
use Febis\SimpleTca\Shortcut\EmailShortcut;
use Febis\SimpleTca\Shortcut\FileShortcut;
use Febis\SimpleTca\Shortcut\InputShortcut;
use Febis\SimpleTca\Shortcut\IRREShortcut;
use Febis\SimpleTca\Shortcut\LinkShortcut;
use Febis\SimpleTca\Shortcut\NumberShortcut;
use Febis\SimpleTca\Shortcut\PassthroughShortcut;
use Febis\SimpleTca\Shortcut\RadioShortcut;
use Febis\SimpleTca\Shortcut\RelationMMShortcut;
use Febis\SimpleTca\Shortcut\RelationShortcut;
use Febis\SimpleTca\Shortcut\SelectSingleShortcut;
use Febis\SimpleTca\Shortcut\SlugShortcut;
use Febis\SimpleTca\Shortcut\TcaShortcutInterface;
use Febis\SimpleTca\Shortcut\TextShortcut;
use Febis\SimpleTca\Shortcut\UuidShortcut;
use ReflectionClass;
use ReflectionException;

/**
 * @codingStandardsIgnoreStart
 * @method static CategoryShortcut createCategory($identifier = null, $minitems = null, $maxitems = null, $treeConfig = [])
 * @method static CheckboxShortcut createCheckbox($identifier = null, $renderType = null)
 * @method static ColorShortcut createColor($identifier = null, $required = false)
 * @method static DatetimeShortcut createDatetime($identifier = null, $required = false)
 * @method static EmailShortcut createEmail($identifier = null, $eval = null, $required = false)
 * @method static FileShortcut createFile($identifier = null, $minitems = null, $maxitems = null, $allowed = null, $as = null)
 * @method static InputShortcut createInput($identifier = null, $eval = null, $renderType = null, $required = false)
 * @method static IRREShortcut createIRRE($identifier = null, $foreignTable = null, $minitems = null, $maxitems = null)
 * @method static LinkShortcut createLink($identifier = null)
 * @method static NumberShortcut createNumber($identifier = null, $format = null, $required = false)
 * @method static PassthroughShortcut createPassthrough($identifier = null)
 * @method static RadioShortcut createRadio($identifier = null, $items = null)
 * @method static RelationMMShortcut createRelationMM($identifier = null, $allowed = null, $mM = null, $mMOppositeField = null, $size = null, $minitems = null, $maxitems = null)
 * @method static RelationShortcut createRelation($identifier = null, $allowed = null, $size = null, $minitems = null, $maxitems = null)
 * @method static SelectSingleShortcut createSelectSingle($identifier = null, $items = null, $renderType = null)
 * @method static SlugShortcut createSlug($identifier = null, $size = null, $eval = null)
 * @method static TextShortcut createText($identifier = null, $enableRichtext = false, $required = false)
 * @method static UuidShortcut createUuid($identifier = null, $version = null, $required = false)
 * @codingStandardsIgnoreEnd
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ShortcutImplementation
{
    use ConfigTrait;
    use DeprecatedShortcuts;

    /**
     * Calls Shortcut Methods
     * @throws MethodNotDefinedException
     * @throws ShortcutNotAllowedException|CallstackExtractionException
     */
    public static function __callStatic(string $name, array $arguments): TcaShortcutInterface
    {
        preg_match('/\Acreate([A-Z][A-z0-9]+)\z/', $name, $match);

        if (isset($match[0], $match[1])) {
            $fqcn = self::getFQCN($match[1]);
            try {
                $reflectionClass = new ReflectionClass($fqcn);
                $shortcutInstance = $reflectionClass->newInstance(...$arguments);
                if ($reflectionClass->implementsInterface(TcaShortcutInterface::class)) {
                    if ($shortcutInstance instanceof AbstractShortcut) {
                        $shortcutInstance->withTablename(static::getConfig()->getTablename());
                    }

                    /** @var TcaShortcutInterface $shortcutInstance */
                    return $shortcutInstance;
                }
            } catch (ReflectionException) {
                throw new ShortcutNotAllowedException(
                    'Shortcut "' . $fqcn . '" was not found or does not implement "' .
                    TcaShortcutInterface::class . '".',
                    1672744035,
                );
            }
        }

        throw new MethodNotDefinedException(
            'Method "' . $name . '" is not defined for "' . self::class . '"',
            1672744033,
        );
    }

    protected static function getFQCN($className): string
    {
        return self::getShortcutNamespace() . $className . 'Shortcut';
    }

    protected static function getShortcutNamespace(): string
    {
        return '\\' . __NAMESPACE__ . '\\Shortcut\\';
    }
}
