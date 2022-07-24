<?php

namespace ex2;

class Helper
{
    public static function findKey($needle, $array, $columnKey)
    {
        return array_search($needle, array_column($array, $columnKey));
    }
}