<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Media;

use WP_CLI;
use WP_CLI_Command;

class Get_Media_Command extends WP_CLI_Command
{

    public function __invoke(array $args, array $assoc_args): void
    {
        WP_CLI::success(
            sprintf('Execute basic command from %s', self::class)
        );
    }
}
