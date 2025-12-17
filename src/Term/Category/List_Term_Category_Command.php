<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term\Category;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\Term_Base_Command;
use Vigihdev\WpCliModels\DTOs\Entities\Terms\CategoryEntityDto;
use Vigihdev\WpCliModels\Entities\CategoryEntity;
use Vigihdev\WpCliModels\UI\CliStyle;


final class List_Term_Category_Command extends Term_Base_Command
{
    public function __construct()
    {
        parent::__construct(name: 'term-category:list');
    }

    /**
     * Menampilkan daftar kategori
     * 
     * ## OPTIONS
     * 
     * [--limit=<limit>]
     * : Jumlah kategori yang akan ditampilkan.
     * ---
     * default: 15
     * ---
     *
     * [--offset=<offset>]
     * : Jumlah kategori yang akan dilewati.
     * ---
     * default: 0
     * ---
     * [--taxonomy=<taxonomy>]
     * : Taxonomy kategori yang akan diambil.
     * ---
     * default: category
     * ---
     * [--order=<order>]
     * : Urutan kategori yang akan diurutkan.
     * ---
     * default: ASC
     * ---
     * [--orderby=<orderby>]
     * : Field yang akan digunakan untuk mengurutkan kategori.
     * ---
     * default: name
     * ---
     * 
     * [--fields=<fields>]
     * : Field yang akan ditampilkan.
     * ---
     * default: term_id,term_name,term_slug,term_taxonomy,count
     * ---
     * [--hide-empty]
     * : Menyembunyikan kategori yang tidak memiliki postingan.
     * ---
     * default: false
     * ---
     * # EXAMPLES
     * 
     * ## EXAMPLES
     * 
     *   wp term-category:list --taxonomy=category --order=ASC --orderby=name --fields=term_id,term_name,term_slug,term_taxonomy,count --hide-empty
     * 
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {

        $collection = CategoryEntity::lists();
        $io = new CliStyle();

        if ($collection->isEmpty()) {
            $io->warning('No term category found.');
            return;
        }

        $this->process($io, $collection);
    }

    /**
     * Memproses dan menampilkan daftar kategori
     * 
     * @param CliStyle $io
     * @param Collection<CategoryEntityDto> $collection
     * @return void
     */
    private function process(CliStyle $io, Collection $collection): void
    {

        $io->title(
            sprintf("📁 %s", $io->textGreen("List Term Category"))
        );

        $items = [];
        foreach ($collection->getIterator() as $i => $category) {
            $items[] = [
                $i + 1,
                $category->getTermId(),
                $category->getName(),
                $category->getSlug(),
                $category->getTaxonomy(),
                $category->getCount(),
            ];
        }
        $io->table($items, ['No', 'term_id', 'term_name', 'term_slug', 'term_taxonomy', 'count']);
        $io->log('');

        $io->line("📊 {$io->textGreen("Summary:")}");
        $io->definitionList([
            'Categories' => $collection->count(),
            'Total Posts' => array_sum($collection->map(fn($cat) => $cat->getCount())->toArray()),
            'Avg Posts/Cat' => $collection->count() > 0 ? array_sum($collection->map(fn($cat) => $cat->getCount())->toArray()) / $collection->count() : 0,
            'Empty' => $collection->filter(fn($cat) => $cat->getSlug() === 'tak-berkategori')->first()->getCategoryCount(),
        ], true);
    }
}
