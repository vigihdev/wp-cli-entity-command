<?php

use Vigihdev\WpCliEntityCommand\Category\{List_Category_Command};
use Vigihdev\WpCliEntityCommand\Taxonomy\{List_Taxonomy_Command};
use Vigihdev\WpCliEntityCommand\Menu\{List_Menu_Command};

if (! class_exists('WP_CLI')) {
    return;
}

$autoloader = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
}

$configs = [
    'Vigihdev\WpCliEntityCommand\Menu' => [
        'menu:list' => 'List_Menu_Commands',
    ],
    'Vigihdev\WpCliEntityCommand\Category' => [
        'category:list' => 'List_Category_Command',
    ],
    'Vigihdev\WpCliEntityCommand\Taxonomy' => [
        'taxonomy:list' => 'List_Taxonomy_Command'
    ],
];

foreach ($configs as $namespace => $commands) {
    foreach ($commands as $command => $className) {
        $class = "{$namespace}\\{$className}";
        if (!class_exists($class)) {
            throw new RuntimeException("{$class} tidak tersedia");
        }
        WP_CLI::add_command($command, new $class());
    }
}
