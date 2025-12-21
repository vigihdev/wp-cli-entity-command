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

final class Update_Menu_Item_Command extends Menu_Base_Command
{

    public function __construct()
    {
        return parent::__construct(name: 'menu-item:update');
    }
    /**
     * Memperbarui menu item berdasarkan ID, slug Atau nama menu.
     *
     * ## OPTIONS
     *
     * <id|slug|name>
     * : ID, slug Atau nama menu item yang akan diperbarui.
     *
     * ## EXAMPLES
     *
     *     # Memperbarui menu item dengan ID 123.
     *     $ wp menu-item:update 123 --title="New Title"
     *     
     *     # Memperbarui menu item dengan slug "primary".
     *     $ wp menu-item:update primary --title="New Title"
     *     
     *     # Memperbarui menu item dengan nama "Primary Menu".
     *     $ wp menu-item:update "Primary Menu" --title="New Title"
     *
     * @param array $args Positional arguments
     * @param array $assoc_args Associative arguments (options)
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        WP_CLI::success(
            sprintf('Execute basic command from %s', Update_Menu_Item_Command::class)
        );
    }
}
