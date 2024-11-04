<?php

namespace Febis\SimpleTca\Data\Typoscript;

use Febis\SimpleTca\Data\AbstractDataHandling;
use Febis\SimpleTca\Data\Cacheable\BaseCacheable;
use Febis\SimpleTca\Data\Cacheable\TyposcriptCacheable;
use Febis\SimpleTca\Exception\CacheInstanceException;
use Febis\SimpleTca\Exception\TyposcriptExistsException;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;

class TyposcriptDataHandling extends AbstractDataHandling
{
    public const BASE_PREFIX = 'lib.tx_simpletca.contentElement';

    public const BASE_DEFAULT = self::BASE_PREFIX . '._default';

    /** @var TyposcriptCacheable $data */
    protected BaseCacheable $data;

    public function __construct(PhpFrontend $cache = null)
    {
        parent::__construct('simpletca_typoscript', $cache);
    }

    #[\Override]
    protected function initData(): void
    {
        $this->data = new TyposcriptCacheable();
    }

    #[\Override]
    protected function debugOutput(): string
    {
        return $this->getFullTyposcript();
    }

    public function getBaseFceItemFor(string $extKey): ?string
    {
        /** @phpstan-ignore-next-line */
        return $this->data->baseFceItems[$extKey]?->getObjectName();
    }

    public function hasBaseFceItemFor(string $extKey): bool
    {
        return isset($this->data->baseFceItems[$extKey]);
    }

    /**
     * @throws CacheInstanceException
     */
    public function addBaseFceItemFor(string $extKey): static
    {
        if (!$this->hasBaseFceItemFor($extKey)) {
            $this->data->baseFceItems[$extKey] = new BaseFceItem($extKey);
        }

        $this->writeData();

        return $this;
    }

    public function hasFceItem(string $identifier): bool
    {
        return isset($this->data->fceItems[$identifier]);
    }

    /**
     * @throws TyposcriptExistsException
     * @throws CacheInstanceException
     */
    public function addFceItem(string $identifier, FceItem $fceItem, bool $override = false): static
    {
        if ($this->hasFceItem($identifier) && !$override) {
            throw new TyposcriptExistsException($identifier);
        } else {
            $this->data->fceItems[$identifier] = $fceItem;
        }

        $this->writeData();

        return $this;
    }

    /**
     * @throws CacheInstanceException
     */
    public function removeFceItem(string $identifier): static
    {
        if ($this->hasFceItem($identifier)) {
            unset($this->data->fceItems[$identifier]);
        }

        $this->writeData();

        return $this;
    }

    public function getFullTyposcript(): ?string
    {
        $typoscript = [];

        foreach ($this->data->baseFceItems as $baseItem) {
            $typoscript[] = $baseItem->generateTyposcript();
        }

        foreach ($this->data->fceItems as $fceItem) {
            $typoscript[] = $fceItem->generateTyposcript();
        }

        return implode("\n", $typoscript);
    }
}
