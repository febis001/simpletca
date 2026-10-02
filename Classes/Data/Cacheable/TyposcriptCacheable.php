<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Data\Cacheable;

use Febis\SimpleTca\Data\Typoscript\BaseFceItem;
use Febis\SimpleTca\Data\Typoscript\FceItem;

class TyposcriptCacheable extends BaseCacheable
{
    /** @var FceItem[] $fceItems */
    public array $fceItems = [];

    /** @var BaseFceItem[] $baseFceItems */
    public array $baseFceItems = [];
}
