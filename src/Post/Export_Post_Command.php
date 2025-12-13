<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;
use WP_CLI_Command;

final class Export_Post_Command extends WP_CLI_Command
{

    /**
     * Export post with specified fields
     *
     * ## OPTIONS
     *
     * [--limit=<limit>]
     * : Number of posts to export
     * ---
     * default: 50
     * ---
     *
     * [--offset=<offset>]
     * : Number of posts to skip
     * ---
     * default: 0
     * ---
     *
     * [--output=<output>]
     * : Write to file instead of STDOUT
     * ---
     * required: true
     * ---
     *
     * [--fields=<fields>]
     * : Comma-separated list of fields to export
     * ---
     * default: ID,post_title,post_content
     * ---
     * 
     * [--dry-run]
     * : Show preview of export without writing to file
     * 
     * [--format=<format>]
     * : Format of export file (json, txt, yaml)
     * ---
     * default: json
     * ---
     * 
     * ## EXAMPLES
     *      
     *      # Export post with ID 123 to JSON file
     *      wp post:export --format=json --fields=ID,post_title,post_content --dry-run
     * 
     *      # Export 20 posts to JSON file with specified fields
     *      wp post:export --limit=20 --offset=0 --output=post-123.json --fields=ID,post_title,post_content
     * 
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();

        WP_CLI::success(
            sprintf('Execute basic command from %s', Export_Post_Command::class)
        );
    }

    private function preProcess() {}
    private function process() {}
    private function exportProcess() {}
    private function dryRunProcess() {}
}
