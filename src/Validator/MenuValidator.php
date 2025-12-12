<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Validator;

use Vigihdev\WpCliEntityCommand\Exceptions\MenuExcetion;

final class MenuValidator
{
    public static function validate(array $menuData): void
    {
        if (empty($menuData['menu-name'])) {
            throw new MenuExcetion('Nama menu tidak boleh kosong');
        }

        if (!empty($menuData['location']) && !array_key_exists($menuData['location'], get_registered_nav_menus())) {
            throw MenuExcetion::invalidLocation($menuData['location']);
        }
    }

    public static function validateId(int $menuId): void
    {
        if (!wp_get_nav_menu_object($menuId)) {
            throw MenuExcetion::notFound($menuId);
        }
    }
}
