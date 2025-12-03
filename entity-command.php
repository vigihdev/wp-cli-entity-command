<?php

use Vigihdev\WpCliEntityCommand\Category\List_Category_Command;

if (! class_exists('WP_CLI')) {
    return;
}

$autoloader = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
}

// Block Category
WP_CLI::add_command('category:list', new List_Category_Command());
