<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term\Category;

use Throwable;
use Vigihdev\WpCliEntityCommand\WP_CLI\Term_Base_Command;
use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Terms\TermEntityDto;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\TermValidator;

final class Get_Term_Category_Command extends Term_Base_Command
{

    public function __construct()
    {
        parent::__construct(name: 'term-category:get');
    }

    /**
     * Get a term category by its slug or ID.
     * 
     * ## OPTIONS
     * 
     * <term>
     * : The term slug or ID.
     * 
     * [--format=<format>]
     * : Export format
     * ---
     * default: table
     * options:
     *   - table
     *   - json
     *   - xml
     * ---
     * 
     * ## EXAMPLES
     * 
     *     # Get a term category by its slug.
     *     $ wp term-category:get category-slug
     *     
     *     # Get a term category by its ID.
     *     $ wp term-category:get 123 --format=json
     * 
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $term = $args[0] ?? null;
        $io = new CliStyle();

        try {
            TermValidator::validate($term, 'category')
                ->mustExist();
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    private function process(CliStyle $io, TermEntityDto $term): void {}
}
