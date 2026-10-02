<?php

declare(strict_types=1);

namespace Febis\SimpleTca\FceGenerator\Showitem;

enum Mode
{
    case Default;
    case Override;
    case DefaultNoHeader;
    case DefaultNoHeaderNoAppearance;
}
