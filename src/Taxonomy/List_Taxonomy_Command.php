<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Taxonomy;

use WP_CLI;
use WP_CLI_Command;
use WP_CLI\Formatter;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliEntityCommand\WP_CLI\Taxonomy_Base_Command;

final class List_Taxonomy_Command extends Taxonomy_Base_Command
{

    public function __construct()
    {
        parent::__construct(name: 'taxonomy:list');
    }

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
        $io = new CliStyle();
    }

    public function process(CliStyle $io): void
    {

        $io->title("📊 List Taxonomies", '%C');
    }
}
