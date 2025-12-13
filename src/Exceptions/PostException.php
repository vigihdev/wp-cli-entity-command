<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Exceptions;

use WP_CLI;

final class PostException extends BaseException
{
    public static function notFound(int $postId): self
    {
        return new self(
            sprintf('Post dengan ID %s tidak ditemukan', self::highlightError((string) $postId)),
            0,
            ['post_id' => $postId]
        );
    }

    public static function invalidPostType(string $postType): self
    {
        return new self(
            sprintf('Tipe post %s tidak valid', self::highlightError($postType)),
            0,
            ['post_type' => $postType]
        );
    }

    public static function updateFailed(int $postId): self
    {
        return new self(
            sprintf('Gagal mengupdate post dengan ID %s', self::highlightError((string) $postId)),
            0,
            ['post_id' => $postId]
        );
    }

    public static function createFailed(): self
    {
        return new self(
            'Gagal membuat post baru',
            0,
            []
        );
    }

    public static function deleteFailed(int $postId): self
    {
        return new self(
            sprintf('Gagal menghapus post dengan ID %s', self::highlightError((string) $postId)),
            0,
            ['post_id' => $postId]
        );
    }

    private static function highlightError(string $message): string
    {
        return WP_CLI::colorize("%R{$message}%n");
    }
}
