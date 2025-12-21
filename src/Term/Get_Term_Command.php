<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term;

use WP_CLI;
use WP_CLI\Utils;
use Vigihdev\WpCliEntityCommand\WP_CLI\Term_Base_Command;
use Vigihdev\WpCliModels\UI\CliStyle;


final class Get_Term_Command extends Term_Base_Command
{
    public function __construct()
    {
        parent::__construct(name: 'term:get');
    }

    /**
     *
     * @param array $args Argumen posisional dari command line
     * @param array $assoc_args Argumen asosiatif dari command line
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        WP_CLI::success(
            sprintf('Execute basic command from %s', Get_Term_Command::class)
        );
    }

    public function process(CliStyle $io): void
    {
        $io->title("📊 Get Terms", '%C');
    }
}
