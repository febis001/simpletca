<?php

namespace Febis\SimpleTca\Configuration\Type;

abstract class AbstractConfig implements ConfigTypeInterface
{
    public ?string $llFile = null;

    public ?string $llFullOverride = null;
}
