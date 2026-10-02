<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Configuration\Type;

class FileConfig extends AbstractConfig
{
    public ?string $tablenameExtracted = null;

    public ?string $tablenameOverride = null;
}
