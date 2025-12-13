<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\UI\Components\DryRunPresetExport;
use WP_CLI;
use WP_CLI\Utils;
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
        $limit = (int) Utils\get_flag_value($assoc_args, 'limit', 50);
        $offset = (int) Utils\get_flag_value($assoc_args, 'offset', 0);
        $output = Utils\get_flag_value($assoc_args, 'output', null);
        $fields = Utils\get_flag_value($assoc_args, 'fields', 'ID,post_title,post_content');
        $dryRun = Utils\get_flag_value($assoc_args, 'dry-run', false);
        $format = Utils\get_flag_value($assoc_args, 'format', 'json');

        if (!$dryRun && $output === null) {
            $io->errorWithIcon('Output file is required');
        }

        if ($dryRun) {
            $this->dryRunProcess($io, $limit, $format, $output);
            return;
        }

        WP_CLI::success(
            sprintf('Execute basic command from %s', Export_Post_Command::class)
        );
    }

    private function preProcess() {}
    private function process() {}
    private function exportProcess() {}
    private function dryRunProcess(CliStyle $io, int $limit, string $format, string $output = null)
    {

        $dryRun = new DryRunPresetExport(
            io: $io,
            name: 'Export Post',
            total: $limit,
            output: $output,
            format: $format
        );
        $dryRun->renderTitle();
    }
}
