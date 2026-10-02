<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Exception;

/** Exception thrown when the ts config definition for the element already exists. */
class TsConfigExistsException extends SimpleTcaException
{
    public function __construct(
        string $identifier,
        bool $group,
        int $code = 1687957187,
        ?\Throwable $previous = null,
    ) {
        $message = 'The TsConfig definition for ' .
            ($group ? 'content element group ' : '') .
            "\"{$identifier}\" already exists.
            If you want to overwrite this configuration, you have to set the \$override parameter to true";
        parent::__construct($message, $code, $previous);
    }
}
