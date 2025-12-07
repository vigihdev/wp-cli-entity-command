<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term;

use WP_CLI;
use WP_CLI_Command;

/**
 * Class Update_Term_Command
 *
 * Class untuk menangani perintah update term
 */
final class Update_Term_Command extends WP_CLI_Command
{
    /**
     * Mengeksekusi perintah update term
     *
     * @param array $args Argumen posisional dari command line
     * @param array $assoc_args Argumen asosiatif dari command line
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        WP_CLI::success(
            sprintf('Execute basic command from %s', Update_Term_Command::class)
        );
    }
}
