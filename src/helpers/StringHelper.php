<?php


namespace PTM\helpers;


class StringHelper
{
    /**
     * @param $name
     * @return string|string[]
     * @TODO 完善文档，修改名称
     */
    public static function formatName($name)
    {
        $string = str_replace('-', ' ', $name);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return $string;
    }
}