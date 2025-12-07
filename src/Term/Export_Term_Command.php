<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term;

use WP_CLI;
use WP_CLI_Command;

final class Export_Term_Command extends WP_CLI_Command
{

    /**
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
