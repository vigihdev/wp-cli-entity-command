<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term;

use WP_CLI;
use WP_CLI_Command;

/**
 * Class Get_Term_Command
 *
 * Class untuk menangani perintah get term
 */
final class Get_Term_Command extends WP_CLI_Command
{
    /**
     * Mengeksekusi perintah get term
     *
     * @param array $args Argumen posisional dari command line
     * @param array $assoc_args Argumen asosiatif dari command line
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        WP_CLI::success(
            sprintf('Execute basic command from %s', Get_Term_Command::class)
        );
    }
}
