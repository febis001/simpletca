<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Debug;

class DebugManager implements DebugInterface
{
    protected array $data = [];

    public static function __set_state(array $state)
    {
        /** @phpstan-ignore-next-line */
        return null;
    }

    #[\Override]
    public function add(mixed $debugData, string | int | null $key = null): void
    {
        if (static::isDebugEnabled()) {
            if ($key === null) {
                $this->data[] = $debugData;
            } else {
                $this->data[$key] = $debugData;
            }
        }
    }

    #[\Override]
    public function get(): array
    {
        return $this->data;
    }

    public static function isDebugEnabled(): bool
    {
        return getenv('SIMPLETCA_DEBUG') === 1;
    }
}
