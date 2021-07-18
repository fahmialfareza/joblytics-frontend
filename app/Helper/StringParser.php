<?php

function initial($string)
{
    $words = preg_split("/[\s,_-]+/", $string);
    $initial = '';
    foreach ($words as $word) {
        $initial .= $word[0];
    }
    return $initial;
}
