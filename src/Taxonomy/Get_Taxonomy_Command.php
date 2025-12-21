<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Taxonomy;

use WP_CLI;
use WP_CLI\Utils;
use WP_CLI_Command;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliEntityCommand\WP_CLI\Taxonomy_Base_Command;

final class Get_Taxonomy_Command extends Taxonomy_Base_Command
{

    public function __construct()
    {
        parent::__construct(name: 'taxonomy:get');
    }

    /**
     * @param array $args Positional arguments
     * @param array $assoc_args Associative arguments (options)
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
    }

    public function process(CliStyle $io): void
    {

        $io->title("📊 Get Taxonomy", '%C');
    }
}
