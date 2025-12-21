<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term;

use WP_CLI;
use WP_CLI\Utils;
use Vigihdev\WpCliEntityCommand\WP_CLI\Term_Base_Command;
use Vigihdev\WpCliModels\DTOs\Entities\Terms\TermEntityDto;
use Vigihdev\WpCliModels\UI\CliStyle;


final class Get_Term_Command extends Term_Base_Command
{
    public function __construct()
    {
        parent::__construct(name: 'term:get');
    }

    /**
     * Mendapatkan term berdasarkan filter ID, slug, atau nama
     * 
     * ## OPTIONS
     * 
     * <term_id|slug|name>
     * : ID, slug, atau nama term yang akan dicari.
     * 
     * ## EXAMPLES
     * 
     * wp term:get 123
     * wp term:get category
     * wp term:get category-name
     * 
     * @param array $args Argumen posisional dari command line
     * @param array $assoc_args Argumen asosiatif dari command line
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        $termId = $args[0] ?? null;

        $terms = $this->getTermDto($termId);
        if (! $terms) {
            $io->renderBlock("Term {$termId} not found")->error();
            die();
        }

        $this->process($io, $terms);
    }

    public function process(CliStyle $io, TermEntityDto $term): void
    {
        $io->title("📊 View Term Name: {$term->getName()}", '%C');
        $io->table(
            items: [
                ['ID', $term->getTermId()],
                ['Name', $term->getName()],
                ['Slug', $term->getSlug()],
                ['Taxonomy', $term->getTaxonomy()],
                ['Description', $term->getDescription()],
                ['Parent', $term->getParent()],
                ['Count', $term->getCount()],
            ],
            fields: ['Field', 'Value'],
        );
    }
}
