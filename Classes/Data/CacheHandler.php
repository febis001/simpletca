<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Data;

use Febis\SimpleTca\Data\Cacheable\CacheableInterface;
use Febis\SimpleTca\Exception\CacheInstanceException;
use LogicException;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Exception\InvalidBackendException;
use TYPO3\CMS\Core\Cache\Exception\InvalidCacheException;
use TYPO3\CMS\Core\Cache\Exception\InvalidDataException;
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Package\Cache\PackageDependentCacheIdentifier;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class CacheHandler
{
    public function __construct(
        protected string $cacheIdentifier,
        protected ?PhpFrontend $codeCache = null,
    ) {
    }

    /**
     * @throws CacheInstanceException
     */
    public static function create(string $cacheIdentifier, ?PhpFrontend $cache = null): static
    {
        /** @phpstan-ignore-next-line */
        $cacheHandler = new static($cacheIdentifier, $cache);
        $cacheHandler->initCache();

        return $cacheHandler;
    }

    /**
     * @throws CacheInstanceException
     */
    public function createCacheFile(CacheableInterface $data): void
    {
        try {
            $this->getCodeCache()->set(
                $this->getConcreteCacheFilename(),
                'return '
                . var_export($data, true)
                . ';',
            );
        } catch (InvalidDataException $invalidDataException) {
            throw new CacheInstanceException($invalidDataException, 1723718256);
        }
    }

    /**
     * @throws CacheInstanceException
     */
    public function readCacheFile(): CacheableInterface | false
    {
        return $this->getCodeCache()->requireOnce($this->getConcreteCacheFilename());
    }

    /**
     * @throws CacheInstanceException
     */
    protected function initCache(): void
    {
        if ($this->codeCache instanceof PhpFrontend) {
            return;
        }

        /**
         * 1. this part should never be executed, as the $cache parameter should be set.
         * 2. if it gets executed, try the default way by instantiate the CacheManager, although the
         * CacheManager is not available when instantiated from ext_localconf.php or at TCA loading.
         * 3. if 2. fails, try to get cache by using the internal static Bootstrap::createCache factory instead
         */
        try {
            try {
                /** @var CacheManager $cacheManager */
                $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
                $codeCache = $cacheManager->getCache($this->cacheIdentifier);
                $this->validateAndSetCache($codeCache);
            } catch (LogicException) {
                $codeCache = Bootstrap::createCache($this->cacheIdentifier);
                $this->validateAndSetCache($codeCache);
            }
        } catch (NoSuchCacheException | InvalidCacheException | InvalidBackendException $previous) {
            throw new CacheInstanceException($previous, 1723718255);
        }
    }

    /**
     * @throws InvalidCacheException
     */
    protected function validateAndSetCache(FrontendInterface $codeCache): void
    {
        if ($codeCache instanceof PhpFrontend) {
            $this->codeCache = $codeCache;
        } else {
            throw new InvalidCacheException(
                sprintf(
                    'Cache is not of expected type "PhpFrontend", but instead of type "%s"',
                    $codeCache::class,
                ),
                1723712874,
            );
        }
    }

    /**
     * @throws CacheInstanceException
     */
    protected function getCodeCache(): PhpFrontend
    {
        if (!$this->codeCache instanceof PhpFrontend) {
            $this->initCache();
        }

        return $this->codeCache;
    }

    protected function getConcreteCacheFilename(): string
    {
        return new PackageDependentCacheIdentifier(GeneralUtility::makeInstance(PackageManager::class))
            ->withPrefix($this->cacheIdentifier)->toString();
    }
}
