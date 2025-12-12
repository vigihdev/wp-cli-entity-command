<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Vigihdev\WpCliModels\Entities\MenuEntity;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuEntityDto;
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
     * 
     * @param array $args Positional arguments
     * @param array $assoc_args Associative arguments (options)
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        WP_CLI::success(
            sprintf('Execute basic command from %s', List_Menu_Item_Command::class)
        );

        $item = MenuEntity::lists();
        $this->process(io: $io, collection: $item);
    }


    /**
     *
     * @param CliStyle $io
     * @param Collection<MenuEntityDto> $collection
     */
    private function process(CliStyle $io, Collection $collection)
    {
        $io->title('📊 View List Menu Item', '%_');

        $io->table(
            fields: ['No', 'name', 'slug', 'taxonomy'],
            items: $collection->map(function ($item, $key) {
                return [
                    $key + 1,
                    $item->getName(),
                    $item->getSlug(),
                    $item->getTaxonomy(),
                ];
            })->toArray(),
        );

        $io->hr('-');
    }
}
