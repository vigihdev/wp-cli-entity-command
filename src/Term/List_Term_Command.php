<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term;

use WP_CLI;
use WP_CLI_Command;

class List_Term_Command extends WP_CLI_Command
{
    /**
     * List terms in any taxonomy
     *
     * ## OPTIONS
     *
     * --taxonomy=<taxonomy>
     * : Taxonomy name (required)
     * 
     * [--format=<format>]
     * : Output format
     * ---
     * default: table
     * ---
     * 
     * [--hide-empty]
     * : Hide empty terms
     * 
     * [--parent=<parent>]
     * : Parent term ID
     * 
     * ## EXAMPLES
     * 
     *     # List categories
     *     $ wp term:list --taxonomy=category
     * 
     *     # List tags
     *     $ wp term:list --taxonomy=post_tag
     * 
     *     # List custom taxonomy terms
     *     $ wp term:list --taxonomy=kota_category
     * 
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        // REQUIRED: taxonomy parameter
        if (!isset($assoc_args['taxonomy'])) {
            WP_CLI::error('Missing required parameter: --taxonomy');
        }

        $taxonomy = $assoc_args['taxonomy'];

        // Validate taxonomy exists
        if (!taxonomy_exists($taxonomy)) {
            WP_CLI::error("Taxonomy '{$taxonomy}' does not exist");
        }

        $term_args = [
            'taxonomy'   => $taxonomy,
            'hide_empty' => isset($assoc_args['hide-empty']),
            'parent'     => $assoc_args['parent'] ?? 0,
        ];

        $terms = get_terms($term_args);

        if (is_wp_error($terms)) {
            WP_CLI::error($terms->get_error_message());
        }

        if (empty($terms)) {
            WP_CLI::warning('No terms found.');
            return;
        }

        $table_data = [];
        foreach ($terms as $term) {
            $table_data[] = [
                'ID'     => $term->term_id,
                'Name'   => $term->name,
                'Slug'   => $term->slug,
                'Count'  => $term->count,
                'Parent' => $term->parent ?: '—',
            ];
        }

        WP_CLI\Utils\format_items(
            $assoc_args['format'] ?? 'table',
            $table_data,
            ['ID', 'Name', 'Slug', 'Count', 'Parent']
        );

        WP_CLI::success(sprintf(
            'Found %d term(s) in taxonomy: %s',
            count($terms),
            $taxonomy
        ));
    }
}
