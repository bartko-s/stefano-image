<?php
set_include_path(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

if(!defined('TEMP_BASE_DIRECOTORY')) {
    define('TEMP_BASE_DIRECOTORY', __DIR__ . '/temp');
}

include_once 'vendor/autoload.php';
include_once 'testConfig.php';