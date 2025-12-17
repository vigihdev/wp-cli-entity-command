<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term\Category;

use Vigihdev\WpCliEntityCommand\WP_CLI\Term_Base_Command;
use Vigihdev\WpCliModels\Enums\Taxonomy;
use Vigihdev\WpCliModels\UI\CliStyle;

final class Export_Term_Category_Command extends Term_Base_Command
{

    public function __construct()
    {
        parent::__construct(name: 'term-category:export');
    }

    /**
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        // array_column(Taxonomy::CATEGORY->cases(), 'value');
    }
}
