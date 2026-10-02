<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Shortcut;

interface TcaShortcutInterface
{
    public function build(): array;

    public function withArguments(array $args): static;
}
