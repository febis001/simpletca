<?php

namespace Febis\SimpleTca\Utility;

class BasicUtility
{
    public static function toUpperCamelCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', strtolower($string))));
    }
}
