<?php

function fill_template($template, $array)
{
    foreach ($array as $key => $value) {
        $key = preg_replace('/\./', '\.', $key);
        $value = preg_replace('/\$/', '\\\$', $value);
        $template = preg_replace(sprintf('/\{\{(%s)\}\}/', $key), $value, $template);
    }
    return $template;
}

function spell_out_int($v)
{
    $map = [
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
    ];
    return $map[$v] ?? $v;
}