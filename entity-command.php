<?php

if (! class_exists('WP_CLI')) {
    return;
}

$autoloader = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
}

$configs = [
    'Vigihdev\WpCliEntityCommand\Menu' => [
        'menu:list' => 'List_Menu_Command',
        'menu:get' => 'Get_Menu_Command',
        'menu:export' => 'Export_Menu_Command',
        'menu:update' => 'Update_Menu_Command',
    ],
    'Vigihdev\WpCliEntityCommand\Menu\Items' => [
        'menu-item:list' => 'List_Menu_Item_Command',
        'menu-item:get' => 'Get_Menu_Item_Command',
        'menu-item:export' => 'Export_Menu_Item_Command',
        'menu-item:update' => 'Update_Menu_Item_Command',
    ],
    'Vigihdev\WpCliEntityCommand\Category' => [
        'category:list' => 'List_Category_Command',
    ],
    'Vigihdev\WpCliEntityCommand\Taxonomy' => [
        'taxonomy:list' => 'List_Taxonomy_Command'
    ],
    'Vigihdev\WpCliEntityCommand\Post' => [
        'post:list' => 'List_Post_Command'
    ],
    'Vigihdev\WpCliEntityCommand\Term' => [
        'term:list' => 'List_Term_Command',
        'term:export' => 'Export_Term_Command',
        'term:get' => 'Get_Term_Command',
        'term:update' => 'Update_Term_Command',
    ],
    'Vigihdev\WpCliEntityCommand\User' => [
        'user:list' => 'List_User_Command'
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
