<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items\Custome;

use Throwable;
use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Vigihdev\WpCliModels\Entities\TermRelationships;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\MenuItemValidator;

final class Get_Menu_Item_Custome_Command extends Menu_Base_Command
{
    private const TYPE = 'custom';
    public function __construct()
    {
        parent::__construct(name: 'menu-item-custome:get');
    }

    /**
     * Mendapatkan item menu kustom
     * 
     * ## DESCRIPTION
     * 
     * Mendapatkan item menu kustom berdasarkan ID.
     * 
     * ## OPTIONS
     * 
     * <menu-item-custom-id>
     * : ID item menu kustom yang akan didapatkan.
     * 
     * required: true
     * 
     * ## EXAMPLES
     * 
     *   # Mendapatkan item menu kustom dengan ID 123
     *   $ wp menu-item-custome:get 123
     * 
     * @param array $args
     * @param array $assoc_args
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        $postId = (int)($args[0] ?? 0);

        try {
            MenuItemValidator::validate($postId)
                ->mustExist();
            $items = iterator_to_array(TermRelationships::findByPostId($postId));
            $item = current($items);
            $this->process($io, $item);
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    private function process(CliStyle $io, TermRelationships $terms): void
    {
        $io->title(
            sprintf("📁 %s", $io->textGreen("Menu Item Kustom"))
        );

        $post = $terms->getPostDto();
        $term = $terms->getTermDto();

        $io->definitionList([
            'ID' => $post->getId(),
            'Nama' => $post->getTitle(),
            'Tipe' => $post->getType(),
            'Taxonomy' => $term->getTaxonomy(),
            'Slug' => $term->getSlug(),
            'Parent' => $term->getParent(),
        ], true);
        $io->log('');
    }
}
