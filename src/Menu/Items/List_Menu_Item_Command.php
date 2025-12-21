<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items;

use WP_CLI;
use WP_CLI\Utils;
use WP_CLI_Command;
use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Vigihdev\WpCliModels\Entities\MenuEntity;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\{MenuEntityDto, MenuItemEntityDto};
use Vigihdev\WpCliModels\Entities\MenuItemEntity;
use Vigihdev\WpCliModels\UI\CliStyle;

final class List_Menu_Item_Command extends Menu_Base_Command
{

    public function __construct()
    {
        return parent::__construct(name: 'menu:item:list');
    }

    /**
     * Menampilkan daftar menu item.
     *
     * ## OPTIONS
     *
     * [--filter=<filter>]
     * : Filter menu item berdasarkan menu name.
     * 
     * ## EXAMPLES
     *
     *     # Menampilkan semua menu item.
     *     $ wp menu item list
     *
     *     # Menampilkan menu item berdasarkan menu name "primary".
     *     $ wp menu item list --filter=primary
     *
     * @param array $args array index
     * @param array $assoc_args Array asosiatif (flag) dari perintah CLI.
     */
    public function __invoke(array $args, array $assoc_args): void
    {

        $io = new CliStyle();
        $filter = Utils\get_flag_value($assoc_args, 'filter');

        $menus = MenuEntity::lists();
        foreach ($menus as $menu) {
            if ($menu instanceof MenuEntityDto) {
                $this->process(io: $io, collection: MenuItemEntity::get($menu->getName()));
            }
        }
    }


    /**
     *
     * @param CliStyle $io
     * @param Collection<MenuItemEntityDto> $collection
     */
    private function process(CliStyle $io, Collection $collection)
    {
        $io->title('📊 View List Menu Item', '%C');

        $io->table(
            fields: ['No', 'ID', 'title', 'url', 'type', 'parentID'],
            items: $collection->map(function ($item, $key) {
                return [
                    $key + 1,
                    $item->getId(),
                    $item->getTitle(),
                    $item->getUrl(),
                    $item->getType(),
                    $item->getParent(),
                ];
            })->toArray(),
        );
    }
}
