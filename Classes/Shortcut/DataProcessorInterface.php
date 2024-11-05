<?php

namespace Febis\SimpleTca\Shortcut;

/**
 * Shortcuts need to implement this interface to enable automatic generation of data processors
 *
 * Important: if you need to add a type for the configuration, implement it like the following example
 *
 *  TypoScript:
 *  dataProcessing.20 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
 *  dataProcessing.20 {
 *    as = images
 *  }
 *
 *  DataProcessorInterface:
 *  getDataProcessorType(): return FilesProcessor::class
 *  getDataProcessorConfig(): return ['as' => 'images']
 */
interface DataProcessorInterface
{
    public function getDataProcessorType(): string;

    public function getDataProcessorConfig(string $fieldName, string $tableName): array;
}
