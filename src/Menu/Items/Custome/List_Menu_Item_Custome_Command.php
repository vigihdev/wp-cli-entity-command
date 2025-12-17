<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items\Custome;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuEntityDto;
use Vigihdev\WpCliModels\Entities\MenuEntity;
use Vigihdev\WpCliModels\Entities\MenuItemEntity;
use Vigihdev\WpCliModels\UI\CliStyle;

final class List_Menu_Item_Custome_Command extends Menu_Base_Command
{
    private const TYPE = 'custom';
    public function __construct()
    {
        parent::__construct(name: 'menu-item-custome:list');
    }

    /**
     * Mencetak daftar item menu kustom
     * 
     * ## DESCRIPTION
     * 
     * Mencetak daftar item menu kustom ke dalam format tabel.
     * 
     * # EXAMPLES
     * 
     * ## EXAMPLES
     * 
     *   # Mencetak semua item menu kustom
     *   $ wp menu-item-custome:list
     * 
     *  # Mencetak item menu kustom dengan menu slug "main-menu"
     *  $ wp menu-item-custome:list --menu=main-menu
     * 
     * @param array $args
     * @param array $assoc_args
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        $menus = MenuEntity::lists();

        if ($menus->isEmpty()) {
            $io->warning('Tidak ada menu kustom yang ditemukan');
            return;
        }

        $io->text($io->textInfo(sprintf('✔ %d item ditampilkan', $menus->count())));

        $summary = [];
        foreach ($menus->getIterator() as $menu) {
            if ($menu instanceof MenuEntityDto) {
                $collection = MenuItemEntity::get($menu->getName())
                    ->filter(function ($item) {
                        return $item->getType() === self::TYPE;
                    });
                $summary[$menu->getName()] = $collection->count();
                $this->process(
                    io: $io,
                    menuName: $menu->getName(),
                    collection: $collection
                );
            }
        }

        $summary = array_merge($summary, ['total' => array_sum(array_values($summary))]);
        $io->log('');
        $io->renderSummary($summary);

        $io->log('');
        $io->text($io->textInfo('💡 Tips: gunakan --menu=<slug> untuk filter menu'));
    }

    private function preProcess(Collection $collection): Collection
    {
        return $collection->filter(function ($item) {
            return $item->getType() === self::TYPE;
        });
    }

    private function process(CliStyle $io, string $menuName, Collection $collection): void
    {

        $io->note(
            $io->textInfo(sprintf('Menu "%s" memiliki %d item', $menuName, $collection->count()))
        );

        $io->title("📊 List Menu Item - {$menuName}", '%C');

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
