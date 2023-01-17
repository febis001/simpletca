<?php

namespace Febis\SimpleTca\Shortcut;

/**
 * To enable auto
 */
interface TcaShortcutInterface
{
    public function build(?string $label = null): array;

    /**
     * @return $this
     */
    public function withArguments(array $args);
}
