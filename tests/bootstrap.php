<?php

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/');
}

require __DIR__ . '/support/wordpress-option-store.php';
require __DIR__ . '/support/wordpress-http.php';
require __DIR__ . '/support/wordpress-availability.php';
require dirname(__DIR__) . '/vendor/autoload.php';
