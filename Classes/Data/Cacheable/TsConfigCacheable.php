<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Data\Cacheable;

use Febis\SimpleTca\Data\TsConfig\FceGroup;
use Febis\SimpleTca\Data\TsConfig\FceItem;

class TsConfigCacheable extends BaseCacheable
{
    /** @var FceItem[] $fceItems */
    public array $fceItems = [];

    /** @var FceGroup[] $fceGroups */
    public array $fceGroups = [];
}
