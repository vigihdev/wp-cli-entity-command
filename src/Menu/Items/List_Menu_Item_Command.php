<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Vigihdev\WpCliModels\Entities\MenuEntity;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuEntityDto;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuItemEntityDto;
use Vigihdev\WpCliModels\Entities\MenuItemEntity;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;
use WP_CLI_Command;

final class List_Menu_Item_Command extends Menu_Base_Command
{

    public function __construct()
    {
        return parent::__construct(name: 'menu:item:list');
    }

    /**
     * Menampilkan daftar item menu.
     *
     * ## OPTIONS
     *
     * [--menu=<menu>]
     * : Slug menu yang akan ditampilkan.
     * ---
     * default: primary
     * ---
     *
     * @param array $args Positional arguments
     * @param array $assoc_args Associative arguments (options)
     */
    public function __invoke(array $args, array $assoc_args): void
    {

        $io = new CliStyle();

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
