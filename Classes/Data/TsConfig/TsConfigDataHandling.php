<?php

namespace Febis\SimpleTca\Data\TsConfig;

use Febis\SimpleTca\Data\AbstractDataHandling;
use Febis\SimpleTca\Data\Cacheable\BaseCacheable;
use Febis\SimpleTca\Data\Cacheable\TsConfigCacheable;
use Febis\SimpleTca\Exception\CacheInstanceException;
use Febis\SimpleTca\Exception\TsConfigExistsException;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;

class TsConfigDataHandling extends AbstractDataHandling
{
    /** @var TsConfigCacheable $data */
    protected BaseCacheable $data;

    public function __construct(PhpFrontend $cache = null)
    {
        parent::__construct('simpletca_tsconfig', $cache);
    }

    #[\Override]
    protected function initData(): void
    {
        $this->data = new TsConfigCacheable();
    }

    #[\Override]
    protected function debugOutput(): string
    {
        return $this->getFullTsConfig();
    }

    public function hasFceItem(string $identifier): bool
    {
        return isset($this->data->fceItems[$identifier]);
    }

    /**
     * @throws TsConfigExistsException
     * @throws CacheInstanceException
     */
    public function addFceItem(string $identifier, FceItem $fceItem, bool $override = false): static
    {
        if ($this->hasFceItem($identifier) && !$override) {
            throw new TsConfigExistsException($identifier, false);
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

    public function hasFceGroup(string $identifier): bool
    {
        return isset($this->data->fceGroups[$identifier]);
    }

    /**
     * @throws CacheInstanceException
     * @throws TsConfigExistsException
     */
    public function addFceGroup(string $identifier, FceGroup $fceGroup, bool $override = false): static
    {
        if ($this->hasFceGroup($identifier) && !$override) {
            throw new TsConfigExistsException($identifier, true);
        } else {
            $this->data->fceGroups[$identifier] = $fceGroup;
        }

        $this->writeData();

        return $this;
    }

    /**
     * @throws CacheInstanceException
     */
    public function removeFceGroup(string $identifier): static
    {
        if ($this->hasFceGroup($identifier)) {
            unset($this->data->fceGroups[$identifier]);
        }

        $this->writeData();

        return $this;
    }

    public function getFullTsConfig(): ?string
    {
        $tsConfig = [];

        foreach ($this->data->fceGroups as $group) {
            $tsConfig[] = $group->generateTsConfig();
        }

        foreach ($this->data->fceItems as $fce) {
            $tsConfig[] = $fce->generateTsConfig();
        }

        return implode("\n", $tsConfig);
    }
}
