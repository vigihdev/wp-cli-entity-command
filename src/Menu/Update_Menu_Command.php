<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu;

use WP_CLI;
use WP_CLI_Command;

/**
 * Class Update_Menu_Command
 *
 * Command to update navigation menu properties
 */
final class Update_Menu_Command extends WP_CLI_Command
{
    /**
     * Update navigation menu properties
     *
     * ## OPTIONS
     *
     * <menu>
     * : The name, slug, or term ID for the menu
     *
     * [--name=<name>]
     * : A new name for the menu
     *
     * [--slug=<slug>]
     * : A new slug for the menu
     *
     * [--description=<description>]
     * : A new description for the menu
     *
     * [--location=<location>]
     * : Assign menu to a theme location (pass '0' to remove from location)
     *
     * ## EXAMPLES
     *
     *     # Update menu name
     *     $ wp menu update primary --name="Primary Menu"
     *
     *     # Update menu slug
     *     $ wp menu update primary --slug="main-navigation"
     *
     *     # Update menu description
     *     $ wp menu update primary --description="Main navigation menu"
     *
     *     # Assign menu to location
     *     $ wp menu update primary --location=primary-menu
     *
     *     # Remove menu from location
     *     $ wp menu update primary --location=0
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

        $updates = [];
        $updated = false;

        // Update name if provided
        if (isset($assoc_args['name'])) {
            $updates['name'] = $assoc_args['name'];
            $updated = true;
        }

        // Update slug if provided
        if (isset($assoc_args['slug'])) {
            $updates['slug'] = $assoc_args['slug'];
            $updated = true;
        }

        // Update description if provided
        if (isset($assoc_args['description'])) {
            $updates['description'] = $assoc_args['description'];
            $updated = true;
        }

        // Perform updates
        if ($updated) {
            $result = wp_update_nav_menu_object($menu->term_id, $updates);

            if (is_wp_error($result)) {
                WP_CLI::error($result->get_error_message());
                return;
            }

            WP_CLI::success("Menu '{$menu->name}' updated.");
        }

        // Handle location assignment
        if (isset($assoc_args['location'])) {
            $this->update_menu_location($menu->term_id, $assoc_args['location']);
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
     * Update menu location assignment
     *
     * @param int $menu_id Menu term ID
     * @param string $location Location to assign menu to, or '0' to remove
     * @return void
     */
    private function update_menu_location(int $menu_id, string $location): void
    {
        $locations = get_nav_menu_locations();

        if ($location === '0') {
            // Remove menu from all locations
            foreach ($locations as $loc => $menu) {
                if ($menu == $menu_id) {
                    unset($locations[$loc]);
                }
            }
            WP_CLI::success("Menu removed from all locations.");
        } else {
            // Assign menu to specific location
            $locations[$location] = $menu_id;
            WP_CLI::success("Menu assigned to location '{$location}'.");
        }

        set_theme_mod('nav_menu_locations', $locations);
    }
}