<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Category;

use WP_CLI;
use WP_CLI_Command;

/**
 * Simple Category List Command
 * 
 * Usage: wp category:list
 */
class List_Category_Command extends WP_CLI_Command
{
    /**
     * List all categories
     * 
     * ## OPTIONS
     * 
     * [--format=<format>]
     * : Output format
     * ---
     * default: table
     * ---
     * 
     * [--hide-empty]
     * : Hide empty categories
     * 
     * ## EXAMPLES
     * 
     *     # List all categories
     *     $ wp category:list
     * 
     *     # List non-empty categories
     *     $ wp category:list --hide-empty
     * 
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $args = [
            'taxonomy'   => 'category',
            'hide_empty' => isset($assoc_args['hide-empty']),
        ];

        $categories = get_categories($args);

        if (empty($categories)) {
            WP_CLI::warning('No categories found.');
            return;
        }

        $table_data = [];
        foreach ($categories as $cat) {
            $table_data[] = [
                'ID'    => $cat->term_id,
                'Name'  => $cat->name,
                'Slug'  => $cat->slug,
                'Count' => $cat->count,
                'Parent' => $cat->parent ?: '—',
            ];
        }

        WP_CLI\Utils\format_items(
            $assoc_args['format'] ?? 'table',
            $table_data,
            ['ID', 'Name', 'Slug', 'Count', 'Parent']
        );

        WP_CLI::success(sprintf('Found %d category(ies)', count($categories)));
    }
}
