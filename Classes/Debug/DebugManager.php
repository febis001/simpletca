<?php

namespace Febis\SimpleTca\Debug;

class DebugManager implements DebugInterface
{
    protected array $data = [];

    #[\Override]
    public function add(mixed $debugData, string | int | null $key = null): void
    {
        if (static::isDebugEnabled()) {
            if (null === $key) {
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
        return getenv('SIMPLETCA_DEBUG') == 1;
    }

    public static function __set_state(array $state)
    {
        /** @phpstan-ignore-next-line */
        return null;
    }
}
