<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items\Custome;

use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Vigihdev\WpCliModels\Entities\MenuItemEntity;
use Vigihdev\WpCliModels\UI\CliStyle;

final class Export_Menu_Item_Custome_Command extends Menu_Base_Command
{
    private const TAXONOMY = 'menu_item';
    public function __construct()
    {
        parent::__construct(name: 'menu-item-custome:export');
    }

    /**
     * Mengekspor item menu kustom
     * 
     * ## DESCRIPTION
     * 
     * Mengekspor item menu kustom ke dalam format JSON.
     * 
     * ## OPTIONS
     * 
     * <menu-item-id>
     * : ID item menu kustom yang akan diekspor.
     * 
     * # EXAMPLES
     * 
     * ## EXAMPLES
     * 
     *   wp menu-item-custome:export 12345
     * 
     * @param array $args
     * @param array $assoc_args
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
    }

    private function process(CliStyle $io, MenuItemEntity $menu): void {}
}
