<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Exception;

/** Exception thrown when there is no identifier set for shortcut build. */
class NoIdentifierException extends SimpleTcaException
{
    public function __construct(
        string $message = '',
        int $code = 1672744034,
        ?\Throwable $previous = null,
    ) {
        $message = $message !== ''
            ? $message
            : 'The required attribute label is not set. Set it first by constructor, withLabel or build method';
        parent::__construct($message, $code, $previous);
    }
}
