<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu;

use WP_CLI;
use WP_CLI_Command;
use WP_CLI\Formatter;

final class List_Menu_Command extends WP_CLI_Command
{
    /**
     * Default fields untuk output
     */
    private const DEFAULT_FIELDS = [
        'term_id',
        'name',
        'slug',
        'count',
        'locations',
        'description',
    ];

    /**
     * List all navigation menus in WordPress
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
     * [--search=<search>]
     * : Search menus by name or description
     *
     * [--location=<location>]
     * : Filter menus by theme location
     *
     * [--has-location]
     * : Show only menus that have a theme location assigned
     *
     * [--no-location]
     * : Show only menus without theme location
     *
     * [--sort=<sort>]
     * : Sort by field (name, count, id)
     * ---
     * default: name
     * ---
     *
     * [--order=<order>]
     * : Sort order (asc, desc)
     * ---
     * default: asc
     * ---
     *
     * [--include-items]
     * : Include menu items count in output
     *
     * [--verbose]
     * : Show extra information about each menu
     *
     * ## EXAMPLES
     *
     *     # List all menus
     *     $ wp menu list
     *
     *     # List menus in JSON format
     *     $ wp menu list --format=json
     *
     *     # List menus with specific fields
     *     $ wp menu list --fields=name,locations,count
     *
     *     # Search menus by name
     *     $ wp menu list --search="primary"
     *
     *     # List menus in a specific location
     *     $ wp menu list --location="primary-menu"
     *
     *     # List menus with items count
     *     $ wp menu list --include-items
     *
     *     # Export menus to CSV
     *     $ wp menu list --format=csv > menus.csv
     *
     * @param array $args Positional arguments
     * @param array $assoc_args Associative arguments (options)
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        // Get all nav menus
        $menus = $this->get_menus($assoc_args);

        if (empty($menus)) {
            WP_CLI::warning('No navigation menus found.');
            return;
        }

        // Prepare data for display
        $data = $this->prepare_menu_data($menus, $assoc_args);

        // Display results
        $this->display_results($data, $assoc_args);

        // Show summary
        WP_CLI::success(sprintf(
            'Found %d menu(s)',
            count($data)
        ));
    }

    /**
     * Get menus dengan filtering
     *
     * @param array $assoc_args
     * @return array
     */
    private function get_menus(array $assoc_args): array
    {
        // Get all nav menu locations
        $locations = get_nav_menu_locations();

        // Get all nav menus
        $menus = wp_get_nav_menus();

        if (empty($menus) || is_wp_error($menus)) {
            return [];
        }

        // Filter by location if specified
        if (isset($assoc_args['location'])) {
            $location = $assoc_args['location'];
            $menus = $this->filter_by_location($menus, $locations, $location);
        }

        // Filter by has-location
        if (isset($assoc_args['has-location'])) {
            $menus = $this->filter_has_location($menus, $locations, true);
        }

        // Filter by no-location
        if (isset($assoc_args['no-location'])) {
            $menus = $this->filter_has_location($menus, $locations, false);
        }

        // Filter by search
        if (isset($assoc_args['search'])) {
            $menus = $this->filter_by_search($menus, $assoc_args['search']);
        }

        // Sort menus
        $menus = $this->sort_menus($menus, $assoc_args);

        return $menus;
    }

    /**
     * Filter menus by theme location
     */
    private function filter_by_location(array $menus, array $locations, string $location): array
    {
        $filtered = [];

        foreach ($menus as $menu) {
            if (
                in_array($menu->term_id, $locations) &&
                array_search($menu->term_id, $locations) === $location
            ) {
                $filtered[] = $menu;
            }
        }

        return $filtered;
    }

    /**
     * Filter menus that have/don't have locations
     */
    private function filter_has_location(array $menus, array $locations, bool $has_location): array
    {
        return array_filter($menus, function ($menu) use ($locations, $has_location) {
            $in_location = in_array($menu->term_id, $locations);
            return $has_location ? $in_location : !$in_location;
        });
    }

    /**
     * Filter menus by search term
     */
    private function filter_by_search(array $menus, string $search): array
    {
        $search = strtolower($search);

        return array_filter($menus, function ($menu) use ($search) {
            $name_match = strpos(strtolower($menu->name), $search) !== false;
            $desc_match = !empty($menu->description) &&
                strpos(strtolower($menu->description), $search) !== false;
            $slug_match = strpos(strtolower($menu->slug), $search) !== false;

            return $name_match || $desc_match || $slug_match;
        });
    }

    /**
     * Sort menus
     */
    private function sort_menus(array $menus, array $assoc_args): array
    {
        $sort_by = $assoc_args['sort'] ?? 'name';
        $order = $assoc_args['order'] ?? 'asc';

        usort($menus, function ($a, $b) use ($sort_by, $order) {
            $value_a = $this->get_sort_value($a, $sort_by);
            $value_b = $this->get_sort_value($b, $sort_by);

            if ($value_a == $value_b) {
                return 0;
            }

            $result = $value_a < $value_b ? -1 : 1;
            return $order === 'desc' ? -$result : $result;
        });

        return $menus;
    }

    /**
     * Get value for sorting
     */
    private function get_sort_value(object $menu, string $sort_by)
    {
        switch ($sort_by) {
            case 'id':
                return $menu->term_id;
            case 'count':
                return $menu->count;
            case 'slug':
                return $menu->slug;
            case 'name':
            default:
                return $menu->name;
        }
    }

    /**
     * Prepare menu data for display
     */
    private function prepare_menu_data(array $menus, array $assoc_args): array
    {
        $locations = get_nav_menu_locations();
        $include_items = isset($assoc_args['include-items']);
        $verbose = isset($assoc_args['verbose']);

        $data = [];

        foreach ($menus as $menu) {
            // Get menu location(s)
            $menu_locations = [];
            foreach ($locations as $location_name => $location_menu_id) {
                if ($location_menu_id == $menu->term_id) {
                    $menu_locations[] = $location_name;
                }
            }

            // Get menu items if requested
            $items_info = '';
            if ($include_items || $verbose) {
                $items = wp_get_nav_menu_items($menu->term_id);
                $items_count = is_array($items) ? count($items) : 0;
                $items_info = $items_count . ' items';

                if ($verbose) {
                    $item_types = [];
                    if (is_array($items)) {
                        foreach ($items as $item) {
                            $type = $item->type ?? 'custom';
                            $item_types[$type] = ($item_types[$type] ?? 0) + 1;
                        }
                    }
                    $items_info .= ' (' . implode(', ', array_map(
                        fn($type, $count) => "$count $type",
                        array_keys($item_types),
                        $item_types
                    )) . ')';
                }
            }

            $row = [
                'term_id'     => $menu->term_id,
                'name'        => $menu->name,
                'slug'        => $menu->slug,
                'count'       => $menu->count,
                'locations'   => !empty($menu_locations) ? implode(', ', $menu_locations) : '—',
                'description' => $menu->description ?: '—',
            ];

            if ($include_items || $verbose) {
                $row['items'] = $items_info;
            }

            if ($verbose) {
                $row['taxonomy'] = $menu->taxonomy;
                $row['parent'] = $menu->parent ?: '—';
            }

            $data[] = $row;
        }

        return $data;
    }

    /**
     * Display results using WP_CLI Formatter
     */
    private function display_results(array $data, array $assoc_args): void
    {
        // Determine fields to show
        $fields = $this->get_output_fields($assoc_args);

        // Create formatter
        $formatter = new Formatter($assoc_args, $fields);

        if ('count' === $formatter->format) {
            WP_CLI::log((string) count($data));
            return;
        }

        $formatter->display_items($data);
    }

    /**
     * Get fields for output based on arguments
     */
    private function get_output_fields(array $assoc_args): array
    {
        $fields = self::DEFAULT_FIELDS;

        // Add extra fields if requested
        if (isset($assoc_args['include-items']) || isset($assoc_args['verbose'])) {
            $fields[] = 'items';
        }

        if (isset($assoc_args['verbose'])) {
            $fields = array_merge($fields, ['taxonomy', 'parent']);
        }

        return $fields;
    }
}
