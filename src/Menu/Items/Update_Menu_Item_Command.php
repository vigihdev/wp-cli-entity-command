<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items;

use WP_CLI;
use WP_CLI_Command;

final class Update_Menu_Item_Command extends WP_CLI_Command
{

    /**
     * 
     * @param array $args Positional arguments
     * @param array $assoc_args Associative arguments (options)
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        WP_CLI::success(
            sprintf('Execute basic command from %s', Update_Menu_Item_Command::class)
        );
    }
}