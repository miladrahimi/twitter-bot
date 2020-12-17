<?php

namespace MiladRahimi\TwitterBot\Utils;

class PercentEncoder
{
    /**
     * Percent encode the input
     *
     * @param array|string $input
     * @return array|string
     */
    public static function encode($input)
    {
        return is_array($input)
            ? array_map([static::class, 'encode'], $input)
            : rawurlencode("$input");
    }
}
