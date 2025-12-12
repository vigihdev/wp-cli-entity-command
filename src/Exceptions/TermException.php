<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Exceptions;

final class TermException extends BaseException
{
    public static function notFound(int $termId): self
    {
        return new self(sprintf('Term dengan ID %d tidak ditemukan', $termId));
    }

    public static function invalidTaxonomy(string $taxonomy): self
    {
        return new self(sprintf('Taxonomy %s tidak valid', $taxonomy));
    }

    public static function updateFailed(int $termId): self
    {
        return new self(sprintf('Gagal mengupdate term dengan ID %d', $termId));
    }

    public static function createFailed(): self
    {
        return new self('Gagal membuat term baru');
    }

    public static function deleteFailed(int $termId): self
    {
        return new self(sprintf('Gagal menghapus term dengan ID %d', $termId));
    }
}
