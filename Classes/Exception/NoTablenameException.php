<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Exception;

/** Exception thrown when there is no tablename set for shortcut build. */
class NoTablenameException extends SimpleTcaException
{
    public function __construct(
        string $message = '',
        int $code = 1693930745,
        ?\Throwable $previous = null,
    ) {
        $message = $message !== ''
            ? $message
            : 'The required attribute tablename is not set. Set it first by constructor or withTablename method';
        parent::__construct($message, $code, $previous);
    }
}
