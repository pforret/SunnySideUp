<?php

namespace Pforret\SunnySideUp\Helpers;

class StringCleaner
{
    public static function name(string $name): string
    {
        return trim(str_replace('PV System Overview', '', $name));
    }

    public static function text(string $text): string
    {
        return trim($text);
    }

    public static function clean(string $text): string
    {
        return ucwords(strtolower(trim($text)));
    }
}
