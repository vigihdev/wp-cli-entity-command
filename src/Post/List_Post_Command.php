<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use WP_CLI;
use WP_CLI_Command;

class List_Post_Command extends WP_CLI_Command
{
    public function __invoke(array $args, array $assoc_args): void
    {
        $query_args = [
            'post_type'      => $assoc_args['post-type'] ?? 'post',
            'post_status'    => $assoc_args['status'] ?? 'publish',
            'posts_per_page' => 5, // Limit untuk testing
        ];

        $posts = get_posts($query_args);

        if (empty($posts)) {
            WP_CLI::warning('No posts found.');
            return;
        }

        $table_data = [];
        foreach ($posts as $post) {
            $table_data[] = [
                'ID'      => $post->ID,
                'Title'   => $post->post_title,
                'Type'    => $post->post_type,
                'Status'  => $post->post_status,
                'Date'    => $post->post_date,
                'Author'  => get_the_author_meta('display_name', $post->post_author),
            ];
        }

        WP_CLI\Utils\format_items(
            $assoc_args['format'] ?? 'table',
            $table_data,
            ['ID', 'Title', 'Type', 'Status', 'Date', 'Author']
        );

        WP_CLI::success(sprintf('Found %d post(s)', count($posts)));
    }
}
