<?php

if (!function_exists('whatsapp_link')) {
    function whatsapp_link($nomor)
    {
        if (substr($nomor, 0, 2) === "08") {
            $bodytag = "628" . substr($nomor, 2);
        } else {
            $bodytag = $nomor;
        }
        return $bodytag;
    }
}
