<?php

namespace Febis\SimpleTca\Configuration;

use Febis\SimpleTca\Configuration\Type\ExtensionConfig;
use Febis\SimpleTca\Configuration\Type\FileConfig;
use Febis\SimpleTca\Configuration\Type\SystemConfig;
use Febis\SimpleTca\Exception\CallstackExtractionException;
use Febis\SimpleTca\Utility\CallStackExtractor;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SimpleTcaConfig implements SingletonInterface, ConfigInterface
{
    use ConfigApi;

    /** @var array{
     *   system: SystemConfig|null,
     *   extension: array<string, ExtensionConfig>,
     *   file: array<string, FileConfig>
     * }
     * $cachedConfigs
     */
    protected array $cachedConfigs = [
        'system' => null,
        'extension' => [],
        'file' => [],
    ];

    protected array $current = [
        'extensionId' => null,
        'fileId' => null,
    ];

    public function __construct()
    {
        $this->cachedConfigs['system'] = new SystemConfig();
        $this->cachedConfigs['system']->llFile = 'contentelements';
    }

    /**
     * @throws CallstackExtractionException
     */
    public function determineAndSetConfig(): void
    {
        [$extkey, $filename, $tablename] = GeneralUtility::makeInstance(CallStackExtractor::class)->extractAll();

        $extensionId = $this->current['extensionId'] = $extkey;
        $fileId = $this->current['fileId'] = $extkey . '-' . $filename;

        if (!isset($this->cachedConfigs['extension'][$extensionId])) {
            $this->cachedConfigs['extension'][$extensionId] = new ExtensionConfig();
            $this->cachedConfigs['extension'][$extensionId]->extKey = $extensionId;
        }

        if (!isset($this->cachedConfigs['file'][$fileId])) {
            $this->cachedConfigs['file'][$fileId] = new FileConfig();
            $this->cachedConfigs['file'][$fileId]->tablenameExtracted = $tablename;
        }
    }
}
