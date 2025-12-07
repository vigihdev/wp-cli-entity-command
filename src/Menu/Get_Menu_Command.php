<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu;

use WP_CLI;
use WP_CLI_Command;
use WP_CLI\Formatter;

/**
 * Class Get_Menu_Command
 *
 * Command to get detailed information about a specific navigation menu
 */
final class Get_Menu_Command extends WP_CLI_Command
{
    /**
     * Get detailed information about a navigation menu
     *
     * ## OPTIONS
     *
     * <menu>
     * : The name, slug, or term ID for the menu
     *
     * [--field=<field>]
     * : Instead of returning the whole menu, returns the value of a single field.
     *
     * [--fields=<fields>]
     * : Limit the output to specific object fields.
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
     * ---
     *
     * [--include-items]
     * : Include detailed information about menu items
     *
     * ## EXAMPLES
     *
     *     # Get menu by slug
     *     $ wp menu get primary
     *
     *     # Get menu by ID
     *     $ wp menu get 5
     *
     *     # Get menu name only
     *     $ wp menu get primary --field=name
     *
     *     # Get menu in JSON format
     *     $ wp menu get primary --format=json
     *
     *     # Get menu with items
     *     $ wp menu get primary --include-items
     *
     * @param array $args Positional arguments
     * @param array $assoc_args Associative arguments (options)
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        list($menu_identifier) = $args;

        // Get menu by identifier
        $menu = $this->get_menu_by_identifier($menu_identifier);

        if (!$menu) {
            WP_CLI::error("Menu '{$menu_identifier}' not found.");
            return;
        }

        // Get menu locations
        $locations = get_nav_menu_locations();
        $menu_locations = [];
        foreach ($locations as $location_name => $location_menu_id) {
            if ($location_menu_id == $menu->term_id) {
                $menu_locations[] = $location_name;
            }
        }

        // Prepare menu data
        $data = [
            'term_id' => $menu->term_id,
            'name' => $menu->name,
            'slug' => $menu->slug,
            'count' => $menu->count,
            'description' => $menu->description ?: '',
            'locations' => $menu_locations,
        ];

        // Include items if requested
        if (isset($assoc_args['include-items'])) {
            $items = wp_get_nav_menu_items($menu->term_id);
            $data['items'] = is_array($items) ? $items : [];
        }

        // Handle field parameter
        if (isset($assoc_args['field'])) {
            $field = $assoc_args['field'];
            if (isset($data[$field])) {
                WP_CLI::log($data[$field]);
            } else {
                WP_CLI::error("Field '{$field}' not found.");
            }
            return;
        }

        // Format output
        $format = $assoc_args['format'] ?? 'table';
        $fields = $assoc_args['fields'] ?? array_keys($data);

        // Special handling for items field
        if (isset($assoc_args['include-items'])) {
            if ($format === 'table') {
                // For table format, we need to handle the items differently
                $items_field = $data['items'];
                unset($data['items']);
                
                $formatter = new Formatter($assoc_args, array_keys($data));
                $formatter->display_item($data);
                
                // Display items separately
                WP_CLI::line("\nMenu Items:");
                if (!empty($items_field)) {
                    $item_formatter = new Formatter(['format' => 'table'], ['ID', 'title', 'url', 'type']);
                    $item_data = array_map(function($item) {
                        return [
                            'ID' => $item->ID,
                            'title' => $item->title,
                            'url' => $item->url,
                            'type' => $item->type,
                        ];
                    }, $items_field);
                    $item_formatter->display_items($item_data);
                } else {
                    WP_CLI::line("No items found.");
                }
            } else {
                // For other formats, output normally
                $formatter = new Formatter($assoc_args, is_array($fields) ? $fields : explode(',', $fields));
                $formatter->display_item($data);
            }
        } else {
            // Standard output without items
            $formatter = new Formatter($assoc_args, is_array($fields) ? $fields : explode(',', $fields));
            $formatter->display_item($data);
        }
    }

    /**
     * Get menu by name, slug or term ID
     *
     * @param string|int $identifier Menu identifier
     * @return \WP_Term|false Menu object or false if not found
     */
    private function get_menu_by_identifier($identifier)
    {
        if (is_numeric($identifier)) {
            return wp_get_nav_menu_object((int) $identifier);
        }

        // Try by slug first
        $menu = wp_get_nav_menu_object($identifier);
        
        if (!$menu) {
            // Try by name
            $menus = wp_get_nav_menus();
            foreach ($menus as $m) {
                if ($m->name === $identifier) {
                    return $m;
                }
            }
        }

        return $menu;
    }
}