<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Exceptions;


final class PostException extends BaseException
{
    public static function notFound(int $postId): self
    {
        return new self(sprintf('Post dengan ID %d tidak ditemukan', $postId));
    }

    public static function invalidPostType(string $postType): self
    {
        return new self(sprintf('Tipe post %s tidak valid', $postType));
    }

    public static function updateFailed(int $postId): self
    {
        return new self(sprintf('Gagal mengupdate post dengan ID %d', $postId));
    }

    public static function createFailed(): self
    {
        return new self('Gagal membuat post baru');
    }

    public static function deleteFailed(int $postId): self
    {
        return new self(sprintf('Gagal menghapus post dengan ID %d', $postId));
    }
} {
}
