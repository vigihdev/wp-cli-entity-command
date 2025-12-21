<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term;

use Vigihdev\Support\Collection;
use WP_CLI;
use WP_CLI\Utils;
use Vigihdev\WpCliEntityCommand\WP_CLI\Term_Base_Command;
use Vigihdev\WpCliModels\UI\CliStyle;

final class List_Term_Command extends Term_Base_Command
{
    public function __construct()
    {
        parent::__construct(name: 'term:list');
    }

    /**
     * List terms
     *
     * [--limit=<limit>]
     * : Batas jumlah term yang ditampilkan
     * ---
     * default: 20
     * ---
     * 
     * [--offset=<offset>]
     * : Offset term yang ditampilkan
     * ---
     * default: 0
     * ---
     * 
     * [--filter=<filter>]
     * : Filter term berdasarkan nama, slug, atau ID
     * 
     * ## EXAMPLES
     *  
     *     # List terms in all taxonomies limit 20
     *     $ wp term:list
     *     
     *     # List terms limit 10
     *     $ wp term:list --limit=10
     * 
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {

        $io = new CliStyle();
        $this->limit = (int) Utils\get_flag_value($assoc_args, 'limit', self::DEFAULT_LIMIT);
        $this->offset = (int) Utils\get_flag_value($assoc_args, 'offset', 0);
        $this->filter = Utils\get_flag_value($assoc_args, 'filter');

        if ($this->limit > self::DEFAULT_LIMIT) {
            $io->renderBlock(sprintf('Limit cannot be greater than %d.', self::DEFAULT_LIMIT))->warning();
            return;
        }
        $terms = $this->getTermsCollection()
            ->slice($this->offset, $this->limit);
        if ($terms->isEmpty()) {
            $io->renderBlock('No terms found.')->info();
            return;
        }

        $this->process($io, $terms);
    }

    public function process(CliStyle $io, Collection $terms): void
    {
        $io->title("📊 List Terms", '%C');

        $items = [];
        foreach ($terms->values() as $index => $term) {
            $items[] = [
                $index + 1,
                $term->getTermId(),
                $term->getName(),
                $term->getSlug(),
                $term->getCount(),
                $term->getParent() ?: '—',
            ];
        }
        $io->table($items, ['No', 'ID', 'Name', 'Slug', 'Count', 'Parent']);

        $io->renderPaginationPreset('Terms', $this->limit, $this->getTermsCollection()->count())->render();
    }
}
