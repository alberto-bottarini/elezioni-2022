<?php

if(!function_exists('format_voti')) {
    function format_voti($voti)
    {
        return number_format($voti, 0, ',', '.');
    }
}

if(!function_exists('format_percentuali')) {
    function format_percentuali($percentuale)
    {
        return number_format($percentuale, 2, ',', '.').'%';
    }
}