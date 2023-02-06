<?php

namespace App\Containers;

abstract class Container
{
    /**
     * @param array $array
     * @return static
     */
    public static function make(array $array)
    {
        $c = new static();
        foreach ($array as $atr => $value) {
            if (property_exists($c, $atr)) {
                $c->{$atr} = $value;
            }
        }
        return $c;
    }
}
