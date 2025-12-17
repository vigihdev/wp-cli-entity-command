<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term\Category;

use Throwable;
use Vigihdev\WpCliEntityCommand\WP_CLI\Term_Base_Command;
use Vigihdev\WpCliModels\DTOs\Entities\Terms\CategoryEntityDto;
use Vigihdev\WpCliModels\Entities\CategoryEntity;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\TermValidator;

final class Delete_Term_Category_Command extends Term_Base_Command
{
    private const TAXONOMY = 'category';
    public function __construct()
    {
        parent::__construct(name: 'term-category:delete');
    }
    /**
     * Delete a term category by its slug or ID.
     * 
     * ## OPTIONS
     * 
     * <term>
     * : The term slug or ID.
     * 
     * [--force]
     * : Force delete the term category.
     * 
     * ## EXAMPLES
     * 
     *     # Delete a term category by its slug.
     *     $ wp term-category:delete category-slug
     *     
     *     # Delete a term category by its ID.
     *     $ wp term-category:delete 123 --force
     * 
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $term = $args[0] ?? null;
        $io = new CliStyle();

        try {
            TermValidator::validate($term, self::TAXONOMY)
                ->mustExist();
            $category = CategoryEntity::get($term);
            $this->process($io, $category);
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }
    private function process(CliStyle $io, CategoryEntityDto $category): void
    {
        // $io->confirm(sprintf('Are you sure you want to delete term category %s?', $category->getName()), $assume_yes: false);
        // $io->success(sprintf('Term category %s has been deleted.', $category->getName()));
    }
}
