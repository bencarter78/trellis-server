<?php

if (! function_exists('generate_uid')) {
    function generateUid($length = 10)
    {
        return str_random($length);
    }
}
