<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Exception;

/** Exception thrown when something is wrong with instantiating the cache. */
class CacheInstanceException extends SimpleTcaException
{
    public function __construct(
        ?\Throwable $previous,
        int $code = 1723712315,
    ) {
        $message = sprintf(
            'An error occurred while trying to instantiate the cache instance: %s (%s)',
            $previous->getMessage(),
            $previous->getCode(),
        );
        parent::__construct($message, $code, $previous);
    }
}
