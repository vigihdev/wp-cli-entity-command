<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items\Custome;

use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Vigihdev\WpCliModels\Entities\{MenuItemEntity, TermRelationships};
use Vigihdev\WpCliModels\Enums\MenuItemType;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\MenuItemValidator;
use WP_CLI\Utils;


final class Delete_Menu_Item_Custome_Command extends Menu_Base_Command
{
    private TermRelationships $termRelations;
    public function __construct()
    {
        parent::__construct(name: 'menu-item-custome:delete');
    }

    /**
     * 
     * Menghapus item menu kustom berdasarkan ID.
     * 
     * ## OPTIONS
     * 
     * <menu-item-id>
     * : ID item menu kustom yang akan dihapus.
     * 
     * [--dry-run]
     * : Menampilkan item menu kustom yang akan dihapus tanpa menghapusnya.
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
        $dryRun = Utils\get_flag_value($assoc_args, 'dry-run', false);

        try {
            MenuItemValidator::validate($id)->mustExist();

            $termRelations = iterator_to_array(TermRelationships::findByPostId($id));
            $termRelation = current($termRelations);
            $this->termRelations = $termRelation;
            MenuItemValidator::validate($id, $termRelation->getTermDto()->getTermId())
                ->mustSameAsItemType(MenuItemType::CUSTOM->value);

            // Process dry run
            if ($dryRun) {
                $this->dryRun($io);
                return;
            }

            // Process pre
            $this->preProcess($io);
        } catch (\Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    private function dryRun(CliStyle $io): void
    {
        $post = $this->termRelations->getPostDto();
        $term = $this->termRelations->getTermDto();
        $dryRun = $io->renderDryRunPreset('Menu item custom delete');

        $dryRun->addInfo(
            "Menu item custom dengan ID {$post->getId()} akan dihapus secara permanen.",
            "Data tidak dapat di kembalikan setelah dihapus.",
            "Jika memiliki child items, mereka mungkin terpengaruh."
        )
            ->addDefinition([
                'ID' => $post->getId(),
                'Title' => $post->getTitle(),
                'Url' => get_permalink($post->getId()),
                'Tipe' => $post->getType(),
                'Taxonomy' => $term->getTaxonomy(),
                'Slug' => $term->getSlug(),
            ])->render();
    }


    private function preProcess(CliStyle $io): void
    {

        $post = $this->termRelations->getPostDto();
        $term = $this->termRelations->getTermDto();
        $menuItem = $this->termRelations->getMenuItemDto();

        $io->renderAsk()->delete(
            dataLabel: 'Menu Item Custom',
            dataItems: [
                'ID' => $post->getId(),
                'Title' => $post->getTitle(),
                'Tipe' => $menuItem->getType(),
                'Created' => $post->getDate(),
                'Taxonomy' => $term->getTaxonomy(),
                'Slug' => $term->getSlug(),
            ],
            extraMessages: [
                '. Jika ada child items, mereka mungkin terpengaruh',
            ]
        );

        $this->process($io, $post->getId());
    }

    private function process(CliStyle $io, int $postId): void
    {

        $deleted = MenuItemEntity::delete($postId);

        if (!$deleted) {
            $io->renderBlock("Menu item custom ID {$postId} failed to delete.")->error();
            return;
        }
        $io->renderBlock("Menu item custom ID {$postId} deleted successfully.")->success();
    }
}
