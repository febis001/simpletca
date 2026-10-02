<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

/**
 * Shortcuts need to implement this interface to enable automatic recursive generation of data processors
 * E.g. a FCE has an IRRE and the related tca table has files
 */
interface RecursiveDataProcessorInterface extends DataProcessorInterface
{
    public function getTcaTable(): string;
}
