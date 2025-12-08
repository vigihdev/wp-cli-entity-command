<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term;

use WP_CLI;
use WP_CLI_Command;

final class Export_Term_Command extends WP_CLI_Command
{


    /**
     * Export terms to JSON, CSV, or XML file.
     *
     * ## OPTIONS
     *
     * --taxonomy=<taxonomy>
     * : (required) The taxonomy slug to export.
     *
     * [--format=<format>]
     * : Output format.
     * ---
     * default: json
     * options:
     *   - json
     *   - csv
     *   - xml
     * ---
     *
     * [--out=<file>]
     * : Output file path.
     * Default: terms-export.{format}
     *
     * [--fields=<fields>]
     * : Comma-separated list of term fields to export.
     * ---
     * default: id,name,slug,description,count,parent
     * options:
     *   - id
     *   - name
     *   - slug
     *   - description
     *   - count
     *   - parent
     *   - taxonomy
     *   - term_group
     *   - term_taxonomy_id
     * ---
     *
     * [--include-empty]
     * : Include terms that have no posts.
     *
     * [--pretty]
     * : Pretty print JSON output.
     *
     * [--dry-run]
     * : Run without actually exporting, just show what would be exported.
     *
     * [--verbose]
     * : Show more detailed output.
     *
     * ## EXAMPLES
     *
     *     # Export kota_category to JSON
     *     $ wp term:export --taxonomy=kota_category --out=kota.json
     *
     *     # Dry run to see what would be exported
     *     $ wp term:export --taxonomy=category --format=csv --dry-run
     *
     *     # Export with specific fields
     *     $ wp term:export --taxonomy=post_tag --fields=id,name,slug --pretty
     *
     * 
     * ## NOTES
     *
     * - The command will create the output directory if it doesn't exist.
     * - For CSV format, the first row will contain column headers.
     * - For XML format, terms will be wrapped in <terms> element.
     * - When using --pretty with JSON, the output will be human-readable.
     *
     * @when after_wp_load
     * 
     * @param array $args Positional arguments
     * @param array $assoc_args Associative arguments (options)
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        WP_CLI::success(
            sprintf('Execute basic command from %s', Export_Term_Command::class)
        );
    }
}
