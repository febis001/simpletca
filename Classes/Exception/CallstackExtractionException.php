<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Exception;

/** Exception thrown when the table could not be parsed. */
class CallstackExtractionException extends SimpleTcaException
{
    public function __construct(
        string $message = '',
        int $code = 1672744036,
        ?\Throwable $previous = null,
    ) {
        $message = $message !== ''
            ? $message
            : 'There is no "Configuration/TCA(/Overrides)" file in calling backtrace found.
            Please set the extkey and tablename yourself by calling "setExtkey" and "setTablename"';
        parent::__construct($message, $code, $previous);
    }
}
