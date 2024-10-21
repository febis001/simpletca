<?php

namespace Febis\SimpleTca;

use Febis\SimpleTca\Configuration\SimpleTcaConfig;
use Febis\SimpleTca\Exception\CallstackExtractionException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait ConfigTrait
{
    protected static ?SimpleTcaConfig $config = null;

    /**
     * @throws CallstackExtractionException
     */
    public static function getConfig(): SimpleTcaConfig
    {
        if (!static::$config instanceof SimpleTcaConfig) {
            static::$config = GeneralUtility::makeInstance(SimpleTcaConfig::class);
        }

        static::$config->determineAndSetConfig();

        return static::$config;
    }
}
