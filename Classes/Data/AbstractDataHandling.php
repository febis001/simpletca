<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Data;

use Febis\SimpleTca\Data\Cacheable\BaseCacheable;
use Febis\SimpleTca\Exception\CacheInstanceException;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;

abstract class AbstractDataHandling
{
    protected BaseCacheable $data;

    protected CacheHandler $cacheHandler;

    /**
     * @throws CacheInstanceException
     */
    public function __construct(string $cacheIdentifier, ?PhpFrontend $cache = null)
    {
        $this->cacheHandler = CacheHandler::create($cacheIdentifier, $cache);
        $this->initData();
        $this->readData();
    }

    public function getTimestamp(): int
    {
        return $this->data->timestamp;
    }

    abstract protected function debugOutput(): mixed;

    abstract protected function initData(): void;

    /**
     * @throws CacheInstanceException
     */
    protected function writeData(): void
    {
        $this->data->addDebugMessage(PHP_EOL . $this->debugOutput() . PHP_EOL, static::class);

        $this->data->timestamp = time();

        $this->cacheHandler->createCacheFile($this->data);
    }

    /**
     * @throws CacheInstanceException
     */
    protected function readData(): void
    {
        $cacheable = $this->cacheHandler->readCacheFile();

        if ($cacheable instanceof BaseCacheable) {
            $this->data = $cacheable;
        }
    }
}
