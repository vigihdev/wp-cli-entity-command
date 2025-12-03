<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Category;

use cli\Table;
use WP_CLI;
use WP_CLI_Command;

final class List_Category_Command extends WP_CLI_Command
{

    /**
     * 
     * Make Pwa Command
     * 
     * ## OPTIONS
     *
     * [--force]
     * : Make Pwa Command
     *
     * ---
     * @param string[] $args Positional arguments. Unused.
     * @param array<string,string>
     */
    public function __invoke(array $args, array $assoc_args)
    {

        WP_CLI::success('List Category Command executed successfully waiting to proggres.');
    }
}
