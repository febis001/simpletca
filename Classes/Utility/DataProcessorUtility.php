<?php

namespace Febis\SimpleTca\Utility;

use Febis\SimpleTca\Data\Typoscript\DataProcessorItem;
use Febis\SimpleTca\Shortcut\DataProcessorInterface;
use Febis\SimpleTca\Shortcut\RecursiveDataProcessorInterface;

class DataProcessorUtility
{
    public static function generate(array $columns): array
    {
        $dataProcessors = [];
        foreach ($columns as $fieldName => $column) {
            if (false === $column instanceof DataProcessorInterface) {
                continue;
            }

            if ($column instanceof RecursiveDataProcessorInterface) {
                $refTable = $column->getTcaTable();
                $refTableColumns = $GLOBALS['TCA'][$refTable]['columns'] ?? [];
                $subDataProcessors = self::generate($refTableColumns);
            }

            $dataProcessors[] = new DataProcessorItem(
                $column->getDataProcessorType(),
                TypoScriptHelper::transformFromTypedTyposcript($column->getDataProcessorConfig($fieldName)),
                $subDataProcessors ?? [],
            );
        }

        return $dataProcessors;
    }
}
