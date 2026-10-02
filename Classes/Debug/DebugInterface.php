<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Debug;

interface DebugInterface
{
    public function add(mixed $debugData, string|int|null $key = null): void;

    public function get(): array;
}
