<?php

declare(strict_types=1);

namespace Febis\SimpleTca\Utility;

class TypoScriptHelper
{
    public const TAB_SIZE_TYPOSCRIPT = 2;

    public const TYPOSCRIPT_COUNTING = 10;

    /**
     * Transforms a php object into a readable typoscript notation
     */
    public static function objectToTextualRepresentation(
        int|string $key,
        array $tsObject,
        int $prevIndent = 0,
    ): string {
        $currentIndent = $prevIndent + self::TAB_SIZE_TYPOSCRIPT;
        $parts = [];
        $parts[] = self::indent($prevIndent) . $key . ' {';

        foreach ($tsObject as $item) {
            if (is_string($item)) {
                $parts[] = self::indent($currentIndent) . $item;
                continue;
            }

            $key = $item[0];
            $value = $item[1];

            if (is_array($value)) {
                $parts[] = self::objectToTextualRepresentation($key, $value, $currentIndent);
            } else {
                $parts[] = self::indent($currentIndent) . $key . ' = ' . $value;
            }
        }

        $parts[] = self::indent($prevIndent) . '}';

        return implode("\n", $parts);
    }

    /**
     * Returns space indent
     */
    public static function indent(int $count): string
    {
        return str_repeat(' ', $count);
    }

    /**
     * Transforms snake_format into UpperCamelCase format
     */
    public static function snakeToCamel(string $input): string
    {
        return implode('', array_map(ucfirst(...), explode('_', $input)));
    }

    public static function transformFromTypedTyposcript(array $input): array
    {
        $output = [];
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                if (isset($value['__type'])) {
                    $output[] = [$key, $value['__type']];
                    unset($value['__type']);
                }

                if ($value !== []) {
                    $output[] = [$key, self::transformFromTypedTyposcript($value)];
                }
            } else {
                $output[] = [$key, $value];
            }
        }

        return $output;
    }
}
