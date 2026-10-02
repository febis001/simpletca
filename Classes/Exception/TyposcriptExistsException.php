<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Exception;

/** Exception thrown when the typoscript definition for the element already exists. */
class TyposcriptExistsException extends SimpleTcaException
{
    public function __construct(
        string $identifier,
        int $code = 1695302730,
        ?\Throwable $previous = null,
    ) {
        $message = "The Typoscript definition for \"{$identifier}\" already exists.
            If you want to overwrite this configuration, you have to set the \$override parameter to true";
        parent::__construct($message, $code, $previous);
    }
}
