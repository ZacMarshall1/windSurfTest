<?php

function base_url($path = '') {
    $base_path = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    return $base_path . '/' . ltrim($path, '/');
}
