<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Exceptions;

final class UserException extends BaseException
{
    public static function notFound(int $userId): self
    {
        return new self(sprintf('User dengan ID %d tidak ditemukan', $userId));
    }

    public static function invalidRole(string $role): self
    {
        return new self(sprintf('Role user %s tidak valid', $role));
    }

    public static function updateFailed(int $userId): self
    {
        return new self(sprintf('Gagal mengupdate user dengan ID %d', $userId));
    }

    public static function createFailed(): self
    {
        return new self('Gagal membuat user baru');
    }

    public static function deleteFailed(int $userId): self
    {
        return new self(sprintf('Gagal menghapus user dengan ID %d', $userId));
    }
}
