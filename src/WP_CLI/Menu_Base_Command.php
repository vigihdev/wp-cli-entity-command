<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\WP_CLI;

use Generator;
use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuEntityDto;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\MenuItemEntityDto;
use Vigihdev\WpCliModels\Entities\MenuEntity;
use Vigihdev\WpCliModels\Entities\MenuItemEntity;
use Vigihdev\WpCliModels\Exceptions\Handler\{HandlerExceptionInterface, WpCliExceptionHandler};
use WP_CLI_Command;

abstract class Menu_Base_Command extends WP_CLI_Command
{
    protected const DEFAULT_LIMIT = 20;
    protected int $limit = 0;
    protected int $offset = 0;
    protected ?string $filter = null;
    protected HandlerExceptionInterface $exceptionHandler;

    public function __construct(
        protected string $name
    ) {
        parent::__construct();
        $this->exceptionHandler = new WpCliExceptionHandler();
    }

    /**
     * List all menus
     * 
     * @return Collection<MenuEntityDto>
     */
    protected function listMenus(): Collection
    {
        return MenuEntity::lists();
    }

    /**
     *
     * @param string $name
     * @return Collection<MenuItemEntityDto>
     */
    protected function getMenuItemDto(string $name): Collection
    {
        return MenuItemEntity::get($name);
    }
    /**
     * Yield all menu items
     * 
     * @return Generator<MenuItemEntityDto>
     */
    protected function yieldMenuItems(): Generator
    {
        foreach ($this->listMenus() as $menu) {
            yield MenuItemEntity::get($menu->getName());
        }
    }
}
