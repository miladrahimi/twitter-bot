<?php

namespace MiladRahimi\TwitterBot\Utils;

class HttpQueryBuilder
{
    /**
     * Build http url query string
     *
     * @param array $params
     * @return string
     */
    public static function build(array $params): string
    {
        if (empty($params)) {
            return '';
        }

        $keys = PercentEncoder::encode(array_keys($params));
        $values = PercentEncoder::encode(array_values($params));
        $params = array_combine($keys, $values);

        uksort($params, 'strcmp');

        $pairs = [];
        foreach ($params as $parameter => $value) {
            if (is_array($value)) {
                sort($value, SORT_STRING);
                foreach ($value as $duplicateValue) {
                    $pairs[] = $parameter . '=' . $duplicateValue;
                }
            } else {
                $pairs[] = $parameter . '=' . $value;
            }
        }

        return implode('&', $pairs);
    }
}
