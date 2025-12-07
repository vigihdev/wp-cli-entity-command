<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu;

use WP_CLI;
use WP_CLI_Command;

/**
 * Class Export_Menu_Command
 *
 * Command to export navigation menus from WordPress
 */
final class Export_Menu_Command extends WP_CLI_Command
{
    /**
     * Export navigation menu to a file
     *
     * ## OPTIONS
     *
     * <menu>
     * : The name, slug, or term ID for the menu
     *
     * [--format=<format>]
     * : Export format
     * ---
     * default: json
     * options:
     *   - json
     *   - xml
     * ---
     *
     * [--filename=<filename>]
     * : Write to file instead of STDOUT
     *
     * [--pretty]
     * : Pretty-print JSON
     *
     * ## EXAMPLES
     *
     *     # Export menu by slug
     *     $ wp menu export primary
     *
     *     # Export menu to file
     *     $ wp menu export primary --filename=primary-menu.json
     *
     *     # Export with pretty printed JSON
     *     $ wp menu export primary --format=json --pretty
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

        // Get menu items
        $items = wp_get_nav_menu_items($menu->term_id);

        // Prepare data for export
        $export_data = [
            'menu' => [
                'term_id' => $menu->term_id,
                'name' => $menu->name,
                'slug' => $menu->slug,
                'description' => $menu->description,
            ],
            'items' => $items,
        ];

        $format = $assoc_args['format'] ?? 'json';

        switch ($format) {
            case 'json':
                $output = $this->export_as_json($export_data, $assoc_args);
                break;

            case 'xml':
                $output = $this->export_as_xml($export_data);
                break;

            default:
                WP_CLI::error("Unsupported format: {$format}");
                return;
        }

        // Output to file or STDOUT
        if (isset($assoc_args['filename'])) {
            file_put_contents($assoc_args['filename'], $output);
            WP_CLI::success("Menu exported to {$assoc_args['filename']}");
        } else {
            echo $output;
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

    /**
     * Export menu data as JSON
     *
     * @param array $data Menu data to export
     * @param array $assoc_args Command arguments
     * @return string JSON formatted data
     */
    private function export_as_json(array $data, array $assoc_args): string
    {
        $options = isset($assoc_args['pretty']) ? JSON_PRETTY_PRINT : 0;
        return json_encode($data, $options);
    }

    /**
     * Export menu data as XML
     *
     * @param array $data Menu data to export
     * @return string XML formatted data
     */
    private function export_as_xml(array $data): string
    {
        $xml = new \SimpleXMLElement('<menu_export/>');
        $this->array_to_xml($data, $xml);
        return $xml->asXML();
    }

    /**
     * Convert array to XML
     *
     * @param array $data Data to convert
     * @param \SimpleXMLElement $xml XML element to populate
     * @return void
     */
    private function array_to_xml(array $data, \SimpleXMLElement $xml): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $child = $xml->addChild(is_string($key) ? $key : 'item');
                $this->array_to_xml($value, $child);
            } else {
                $xml->addChild(is_string($key) ? $key : 'item', htmlspecialchars((string) $value));
            }
        }
    }
}
