<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Debug;

use TYPO3\CMS\Core\Utility\GeneralUtility;

trait DebugAwareTrait
{
    protected ?DebugInterface $debug = null;

    public function setDebug(DebugInterface $debug): void
    {
        if ($this->isDebugEnabled()) {
            $this->debug = $debug;
        }
    }

    public function addDebugMessage(string $message, ?string $key = null): void
    {
        $this->debug?->add($message, $key);
    }

    protected function initDebug(): void
    {
        $this->setDebug(GeneralUtility::makeInstance(DebugManager::class));
    }

    protected function isDebugEnabled(): bool
    {
        return DebugManager::isDebugEnabled();
    }
}
