<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Exceptions;

final class MenuExcetion extends BaseException
{
    public static function notFound(int $menuId): self
    {
        return new self(sprintf('Menu dengan ID %d tidak ditemukan', $menuId));
    }

    public static function invalidLocation(string $location): self
    {
        return new self(sprintf('Lokasi menu %s tidak valid', $location));
    }

    public static function updateFailed(int $menuId): self
    {
        return new self(sprintf('Gagal mengupdate menu dengan ID %d', $menuId));
    }

    public static function createFailed(): self
    {
        return new self('Gagal membuat menu baru');
    }

    public static function deleteFailed(int $menuId): self
    {
        return new self(sprintf('Gagal menghapus menu dengan ID %d', $menuId));
    }
}
