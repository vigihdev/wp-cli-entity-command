<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu;

use WP_CLI;
use WP_CLI_Command;
use WP_CLI\Formatter;

final class List_Menu_Command extends WP_CLI_Command
{

    /**
     * 
     * @param array $args Positional arguments
     * @param array $assoc_args Associative arguments (options)
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        WP_CLI::success(self::class . ' executed successfully waiting to proggres.');
    }
}
