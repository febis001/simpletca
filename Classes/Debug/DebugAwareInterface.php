<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Debug;

interface DebugAwareInterface
{
    public function setDebug(DebugInterface $debug): void;

    public function addDebugMessage(string $message, ?string $key = null): void;
}
