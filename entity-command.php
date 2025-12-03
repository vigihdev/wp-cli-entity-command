<?php

use Vigihdev\WpCliEntityCommand\Category\{List_Category_Command};
use Vigihdev\WpCliEntityCommand\Taxonomy\{List_Taxonomy_Command};

if (! class_exists('WP_CLI')) {
    return;
}

$autoloader = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
}

// Block Category
WP_CLI::add_command('category:list', new List_Category_Command());

// Block Taxonomy Command
WP_CLI::add_command('taxonomy:list', new List_Taxonomy_Command());
