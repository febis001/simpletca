<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Exception;

use TYPO3\CMS\Core\Exception;

/** Prefixes exceptions with extension name. */
class SimpleTcaException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('SimpleTCA: ' . $message, $code, $previous);
    }
}
