<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use Vigihdev\WpCliEntityCommand\WP_CLI\Post_Base_Command;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\UI\Components\ListPreset;
use WP_CLI;
use WP_CLI_Command;

final class List_Post_Command extends Post_Base_Command
{

    public function __construct()
    {
        return parent::__construct(name: 'post:list');
    }

    /**
     * wp post:list
     * 
     * Menampilkan daftar post.
     *
     * ## OPTIONS
     * 
     * [--limit=<limit>]
     * : Jumlah post yang akan ditampilkan.
     * ---
     * default: 15
     * ---
     *
     * [--offset=<offset>]
     * : Jumlah post yang akan dilewati.
     * ---
     * default: 0
     * ---
     *
     * [--post-type=<type>]
     * : Tipe post yang akan diambil.
     * ---
     * default: post
     * ---
     *
     * [--status=<status>]
     * : Status post yang akan diambil.
     * ---
     * default: publish
     * ---
     *
     * ## EXAMPLES
     *
     *     wp post:list --limit=20 --offset=5
     *
     * @param array $args       Argumen posisional dari perintah CLI.
     * @param array $assoc_args Argumen asosiatif (flag) dari perintah CLI.
     */
    public function __invoke(array $args, array $assoc_args)
    {
        WP_CLI::success(
            sprintf('Execute basic command from %s', Get_Post_Command::class)
        );
    }

    private function process() {}
}
