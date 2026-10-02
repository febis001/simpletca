<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Exception;

/** Exception thrown when the method is not yet implemented. */
class NotImplementedException extends SimpleTcaException
{
    public function __construct(
        string $methodName = '',
        int $code = 1672916415,
        ?\Throwable $previous = null,
    ) {
        $methodName = $methodName !== '' ? sprintf('"%s"', $methodName) : '';
        $message = sprintf('The method %s is not implemented yet.', $methodName);
        parent::__construct($message, $code, $previous);
    }
}
