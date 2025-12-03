<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Taxonomy;

use cli\Table;
use WP_CLI;
use WP_CLI_Command;

final class List_Taxonomy_Command extends WP_CLI_Command
{

    /**
     * @param string[] $args Positional arguments. Unused.
     * @param array<string,string>
     */
    public function __invoke(array $args, array $assoc_args)
    {

        WP_CLI::success(self::class . " executed successfully waiting to proggres.");
    }
}
