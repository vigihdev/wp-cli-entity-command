<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use WP_CLI;
use WP_CLI_Command;

final class Update_Post_Command extends WP_CLI_Command
{

    /**
     *
     * @param array $args
     * @param array $assoc_args
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        WP_CLI::success(
            sprintf('Execute basic command from %s', Update_Post_Command::class)
        );
    }

    private function process() {}
}
