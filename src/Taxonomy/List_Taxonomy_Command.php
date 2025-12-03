<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Taxonomy;

use WP_CLI;
use WP_CLI_Command;
use WP_CLI\Formatter;

final class List_Taxonomy_Command extends WP_CLI_Command
{

    /**
     * Lists all registered taxonomies in WordPress.
     *
     * ## OPTIONS
     *
     * [--format=<format>]
     * : Render output in a particular format.
     * ---
     * default: table
     * options:
     *   - table
     *   - json
     *   - csv
     *   - yaml
     *   - count
     * ---
     *
     * [--fields=<fields>]
     * : Limit the output to specific object fields.
     * 
     * [--object-type=<object-type>]
     * : Filter taxonomies by object type (post, user, etc.)
     *
     * [--public=<public>]
     * : Filter by public status (true/false)
     *
     * [--hierarchical=<hierarchical>]
     * : Filter by hierarchical status (true/false)
     *
     * [--show-builtin]
     * : Include built-in taxonomies (category, post_tag, etc.)
     *
     * [--search=<search>]
     * : Search taxonomies by name or label
     *
     * ## EXAMPLES
     *
     *     # List all taxonomies
     *     $ wp entity taxonomy list
     *
     *     # List taxonomies in JSON format
     *     $ wp entity taxonomy list --format=json
     *
     *     # List taxonomies for posts only
     *     $ wp entity taxonomy list --object-type=post
     *
     *     # List only hierarchical taxonomies
     *     $ wp entity taxonomy list --hierarchical=true
     *
     *     # List taxonomies with specific fields
     *     $ wp entity taxonomy list --fields=name,label,object_type
     *
     * @param array $args Positional arguments
     * @param array $assoc_args Associative arguments (options)
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        // Get all registered taxonomies
        $taxonomies = get_taxonomies([], 'objects');

        // Filter by object type if specified
        if (isset($assoc_args['object-type'])) {
            $object_type = $assoc_args['object-type'];
            $taxonomies = array_filter($taxonomies, function ($taxonomy) use ($object_type) {
                return in_array($object_type, $taxonomy->object_type);
            });
        }

        // Filter by public status if specified
        if (isset($assoc_args['public'])) {
            $public = filter_var($assoc_args['public'], FILTER_VALIDATE_BOOLEAN);
            $taxonomies = array_filter($taxonomies, function ($taxonomy) use ($public) {
                return $taxonomy->public === $public;
            });
        }

        // Filter by hierarchical status if specified
        if (isset($assoc_args['hierarchical'])) {
            $hierarchical = filter_var($assoc_args['hierarchical'], FILTER_VALIDATE_BOOLEAN);
            $taxonomies = array_filter($taxonomies, function ($taxonomy) use ($hierarchical) {
                return $taxonomy->hierarchical === $hierarchical;
            });
        }

        // Filter out built-in taxonomies if not specified
        if (!isset($assoc_args['show-builtin'])) {
            $taxonomies = array_filter($taxonomies, function ($taxonomy) {
                return !in_array($taxonomy->name, ['category', 'post_tag', 'nav_menu', 'link_category', 'post_format']);
            });
        }

        // Search filter if specified
        if (isset($assoc_args['search'])) {
            $search = strtolower($assoc_args['search']);
            $taxonomies = array_filter($taxonomies, function ($taxonomy) use ($search) {
                return strpos(strtolower($taxonomy->name), $search) !== false ||
                    strpos(strtolower($taxonomy->label), $search) !== false;
            });
        }

        // Prepare data for output
        $data = [];
        foreach ($taxonomies as $taxonomy) {
            $data[] = [
                'name'           => $taxonomy->name,
                'label'          => $taxonomy->label,
                'object_type'    => implode(', ', $taxonomy->object_type),
                'public'         => $taxonomy->public ? '✓' : '✗',
                'hierarchical'   => $taxonomy->hierarchical ? '✓' : '✗',
                'show_ui'        => $taxonomy->show_ui ? '✓' : '✗',
                'show_in_menu'   => $taxonomy->show_in_menu ? '✓' : '✗',
                'show_admin_column' => $taxonomy->show_admin_column ? '✓' : '✗',
                'rewrite'        => $taxonomy->rewrite ? '✓' : '✗',
                'query_var'      => $taxonomy->query_var ? '✓' : '✗',
                'capabilities'   => implode(', ', array_keys((array)$taxonomy->cap)),
            ];
        }

        // Default fields if not specified
        $default_fields = ['name', 'label', 'object_type', 'public', 'hierarchical'];

        // Create formatter
        $formatter = new Formatter($assoc_args, $default_fields);

        // Display results
        if ('count' === $formatter->format) {
            WP_CLI::log(count($data));
        } else {
            $formatter->display_items($data);
        }

        // Show summary
        if (empty($data)) {
            WP_CLI::warning('No taxonomies found.');
        } else {
            WP_CLI::success(sprintf('Found %d taxonomy(ies)', count($data)));
        }
    }
}
