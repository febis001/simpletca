<?php

namespace Febis\SimpleTca\Utility;

use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\DriverManager;
use Febis\SimpleTca\Exception\CallstackExtractionException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CallStackExtractor implements SingletonInterface
{
    protected ?array $tableList = null;

    /**
     * @throws CallstackExtractionException
     */
    public function extractExtkeyAndTablename(): array
    {
        [$extkey, $_, $realTablename] = $this->extractAll();
        return [$extkey, $realTablename];
    }

    /**
     * @throws CallstackExtractionException
     */
    public function extractExtkeyAndFilename(): array
    {
        return $this->filterExtkeyAndFilename();
    }

    /**
     * @throws CallstackExtractionException
     */
    public function extractAll(): array
    {
        [$extkey, $filename] = $this->filterExtkeyAndFilename();
        $realTablename = $this->getRealTablename($filename);

        return [$extkey, $filename, $realTablename];
    }

    /**
     * @throws CallstackExtractionException
     */
    protected function filterExtkeyAndFilename(): array
    {
        $fileMatch = $this->filterStacktrace('/[^\/]+\/([^\/]+)\/Configuration\/TCA\/(Overrides\/)?([^.]+)\.php/');

        $extkey = $fileMatch[1] ?? null;
        $filename = $fileMatch[3] ?? null;

        return [$extkey, $filename];
    }

    /**
     * @throws CallstackExtractionException
     */
    protected function filterStacktrace(string $pattern): array
    {
        $stackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);

        foreach ($stackTrace as $traceItem) {
            preg_match(
                $pattern,
                $traceItem['file'] ?? '',
                $fileMatch,
            );

            if ($fileMatch !== []) {
                return $fileMatch;
            }
        }

        throw new CallstackExtractionException();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function getTableList(): array
    {
        if (null === $this->tableList) {
            if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() < 12) {
                $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
            } else {
                $connectionParams = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'];
                $connection = DriverManager::getConnection($connectionParams);
            }

            $this->tableList = $connection
                ->prepare("SHOW TABLES;")
                ->executeQuery()
                ->fetchFirstColumn();

            sort($this->tableList);
        }

        return $this->tableList;
    }

    protected function getRealTablename(string $tablename): string
    {
        if (in_array($tablename, $this->getTableList(), true)) {
            return $tablename;
        }

        $partialMatch = array_filter(
            $this->tableList,
            static function ($realTablename) use ($tablename) {
                preg_match(sprintf('/^%s.*$/', $realTablename), $tablename, $match);
                return isset($match[0]);
            },
        );

        return reset($partialMatch) ?: $tablename;
    }
}
