<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\User;

use WP_CLI;
use WP_CLI_Command;

class List_User_Command extends WP_CLI_Command
{
    /**
     * List all WordPress users
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
     * [--role=<role>]
     * : Filter users by role
     *
     * [--search=<search>]
     * : Search users by username, email, or display name
     *
     * [--fields=<fields>]
     * : Limit output to specific fields
     *
     * [--per-page=<per-page>]
     * : Number of users per page
     * ---
     * default: 20
     * ---
     *
     * [--page=<page>]
     * : Page number
     * ---
     * default: 1
     * ---
     *
     * [--orderby=<orderby>]
     * : Order by field
     * ---
     * default: login
     * options:
     *   - id
     *   - login
     *   - email
     *   - registered
     *   - display_name
     * ---
     *
     * [--order=<order>]
     * : Sort order
     * ---
     * default: asc
     * options:
     *   - asc
     *   - desc
     * ---
     *
     * [--who=<who>]
     * : Filter by user type
     * ---
     * default: all
     * options:
     *   - all
     *   - authors
     * ---
     *
     * ## EXAMPLES
     *
     *     # List all users
     *     $ wp user:list
     *
     *     # List administrators only
     *     $ wp user:list --role=administrator
     *
     *     # Search for users
     *     $ wp user:list --search="john"
     *
     *     # List users in JSON format
     *     $ wp user:list --format=json
     *
     *     # List authors only
     *     $ wp user:list --who=authors
     *
     *     # Export users to CSV
     *     $ wp user:list --format=csv > users.csv
     *
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        // Build query arguments
        $query_args = [
            'role'    => $assoc_args['role'] ?? '',
            'search'  => isset($assoc_args['search']) ? '*' . $assoc_args['search'] . '*' : '',
            'number'  => (int) ($assoc_args['per-page'] ?? 20),
            'paged'   => (int) ($assoc_args['page'] ?? 1),
            'orderby' => $assoc_args['orderby'] ?? 'login',
            'order'   => strtoupper($assoc_args['order'] ?? 'asc'),
        ];

        // Filter by who (authors, etc)
        if (isset($assoc_args['who']) && $assoc_args['who'] === 'authors') {
            $query_args['who'] = 'authors';
        }

        // Get users
        $users = get_users($query_args);

        if (empty($users)) {
            WP_CLI::warning('No users found.');
            return;
        }

        // Prepare data for display
        $data = [];
        foreach ($users as $user) {
            // Get user roles
            $roles = !empty($user->roles) ? implode(', ', $user->roles) : '—';

            // Get post count if available
            $post_count = '';
            if (function_exists('count_user_posts')) {
                $post_count = count_user_posts($user->ID);
            }

            $data[] = [
                'ID'           => $user->ID,
                'Username'     => $user->user_login,
                'Email'        => $user->user_email,
                'Display Name' => $user->display_name,
                'Roles'        => $roles,
                'Registered'   => $user->user_registered,
                'Posts'        => $post_count,
            ];
        }

        // Default fields
        $default_fields = ['ID', 'Username', 'Email', 'Display Name', 'Roles', 'Registered'];

        // Add posts count if who=authors or explicitly requested
        if ((isset($assoc_args['who']) && $assoc_args['who'] === 'authors') ||
            (isset($assoc_args['fields']) && strpos($assoc_args['fields'], 'Posts') !== false)
        ) {
            $default_fields[] = 'Posts';
        }

        // Display results
        WP_CLI\Utils\format_items(
            $assoc_args['format'] ?? 'table',
            $data,
            $assoc_args['fields'] ?? $default_fields
        );

        // Get total count for pagination info
        $total_users = count_users();
        $total_count = $total_users['total_users'];

        WP_CLI::success(sprintf(
            'Showing %d of %d user(s)',
            count($users),
            $total_count
        ));

        // Pagination hint if there are more users
        if ($total_count > count($users)) {
            $current_page = (int) ($assoc_args['page'] ?? 1);
            $total_pages = ceil($total_count / $query_args['number']);

            if ($total_pages > 1) {
                WP_CLI::log(sprintf(
                    'Page %d of %d. Use --page=<page> to see more.',
                    $current_page,
                    $total_pages
                ));
            }
        }
    }
}
