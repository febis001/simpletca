<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Utility;

use Febis\SimpleTca\Data\Typoscript\DataProcessorItem;
use Febis\SimpleTca\Shortcut\AbstractShortcut;
use Febis\SimpleTca\Shortcut\DataProcessorInterface;
use Febis\SimpleTca\Shortcut\RecursiveDataProcessorInterface;

class DataProcessorUtility
{
    public static function generate(array $columns, string $table = 'tt_content'): array
    {
        $dataProcessors = [];
        foreach ($columns as $fieldName => $column) {
            if ($column instanceof DataProcessorInterface === false) {
                continue;
            }

            if ($column instanceof RecursiveDataProcessorInterface) {
                $refTable = $column->getTcaTable();
                $refTableColumns = AbstractShortcut::getOriginalColumns($refTable);
                $subDataProcessors = self::generate($refTableColumns, $refTable);
            }

            $dataProcessors[] = new DataProcessorItem(
                $column->getDataProcessorType(),
                TypoScriptHelper::transformFromTypedTyposcript($column->getDataProcessorConfig($fieldName, $table)),
                $subDataProcessors ?? [],
            );
        }

        return $dataProcessors;
    }
}
