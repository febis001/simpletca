<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Data\Cacheable;

use Febis\SimpleTca\Debug\DebugAwareInterface;
use Febis\SimpleTca\Debug\DebugAwareTrait;

abstract class AbstractCacheable implements CacheableInterface, DebugAwareInterface
{
    use DebugAwareTrait;

    public function __construct()
    {
        $this->initDebug();
    }

    public static function __set_state(array $data)
    {
        /** @phpstan-ignore-next-line */
        $newObj = new static();

        if (isset($data['debug'])) {
            unset($data['debug']);
        }

        foreach ($data as $propKey => $propValue) {
            $newObj->{$propKey} = $propValue;
        }

        return $newObj;
    }
}
