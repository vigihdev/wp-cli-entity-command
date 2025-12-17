<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items\Custome;

use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Vigihdev\WpCliModels\Entities\MenuItemEntity;
use Vigihdev\WpCliModels\Entities\TermRelationships;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\MenuItemValidator;
use WP_CLI\Context\Cli;

final class Delete_Menu_Item_Custome_Command extends Menu_Base_Command
{
    private const TYPE = 'custom';
    public function __construct()
    {
        parent::__construct(name: 'menu-item-custome:delete');
    }

    /**
     * Menghapus item menu kustom
     * 
     * ## DESCRIPTION
     * 
     * Menghapus item menu kustom berdasarkan ID.
     * 
     * ## OPTIONS
     * 
     * <menu-item-id>
     * : ID item menu kustom yang akan dihapus.
     * 
     * # EXAMPLES
     * 
     * ## EXAMPLES
     * 
     *   wp menu-item-custome:delete 12345
     * 
     * @param array $args
     * @param array $assoc_args
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        $id = (int)($args[0] ?? 0);

        try {
            MenuItemValidator::validate($id)
                ->mustExist();
            $items = iterator_to_array(TermRelationships::findByPostId($id));
            $item = current($items);
            $this->preProcess($io, $item);
        } catch (\Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    private function preProcess(CliStyle $io, TermRelationships $terms): void
    {

        $post = $terms->getPostDto();
        $term = $terms->getTermDto();
        $io->renderAsk()->delete(
            dataLabel: 'Menu Item Kustom',
            dataItems: [
                'ID' => $post->getId(),
                'Nama' => $post->getTitle(),
                'Tipe' => $post->getType(),
                'Taxonomy' => $term->getTaxonomy(),
                'Slug' => $term->getSlug(),
            ],
            extraMessages: [
                '. Jika ada child items, mereka mungkin terpengaruh',
            ]
        );
    }

    private function process(CliStyle $io, MenuItemEntity $menu): void {}
}
