<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items\Custome;

use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Vigihdev\WpCliModels\Entities\MenuItemEntity;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP;
use WP_CLI;

final class Update_Menu_Item_Custome_Command extends Menu_Base_Command
{
    private const TAXONOMY = 'menu_item';
    public function __construct()
    {
        parent::__construct(name: 'menu-item-custome:update');
    }

    /**
     * Memperbarui item menu kustom
     * 
     * ## DESCRIPTION
     * 
     * Memperbarui item menu kustom berdasarkan ID.
     * 
     * ## OPTIONS
     * 
     * <menu-item-id>
     * : ID item menu kustom yang akan diperbarui.
     * 
     * # EXAMPLES
     * 
     * ## EXAMPLES
     * 
     *   wp menu-item-custome:update 12345 --title="New Title"
     * 
     * @param array $args
     * @param array $assoc_args
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        $menuId = (int)($args[0] ?? 0);

        $message = sprintf('Item menu kustom dengan ID %d berhasil diperbarui', $menuId);
        $io->renderBlock($message)->success();
        $io->log('');
        $io->renderBlock("Item menu kustom dengan ID {$menuId} gagal diperbarui")->error();
    }

    private function preProcess(CliStyle $io, MenuItemEntity $menuItem): void {}

    private function process(CliStyle $io, MenuItemEntity $menu): void {}
}
