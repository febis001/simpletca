<?php

declare(strict_types=1);

namespace Febis\SimpleTca;

use Febis\SimpleTca\Shortcut\RteShortcut;
use Febis\SimpleTca\Utility\ErrorUtility;

/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameters)
 */
trait DeprecatedShortcuts
{
    /**
     * @deprecated use TcaGenerator::createText instead
     */
    public static function createRte(
        $identifier = null
    ): RteShortcut {
        ErrorUtility::triggerDeprecated('::createRte', TcaGenerator::class . '::createText');

        /** @phpstan-var RteShortcut $rte */
        $rte = static::__callStatic('createRte', func_get_args());
        return $rte;
    }

    ///**
    // * @deprecated use TcaGenerator::createXY instead
    // */
    //public static function createYZ(
    //    $identifier = null,
    //    $minitems = null,
    //    $maxitems = null,
    //    $fieldname = null,
    //): XYShortcut {
    //    ErrorUtility::triggerDeprecated('::createYZ', TcaGenerator::class . '::createXY');
    //
    //    /** @phpstan-var XYShortcut $image */
    //    $xy = static::__callStatic('createXY', func_get_args());
    //    return $xy;
    //}
}
