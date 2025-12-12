<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Validator;

use Vigihdev\WpCliEntityCommand\Exceptions\TermException;

final class TermValidator
{
    public static function validate(array $termData): void
    {
        if (empty($termData['name'])) {
            throw new TermException('Nama term tidak boleh kosong');
        }

        if (empty($termData['taxonomy']) || !taxonomy_exists($termData['taxonomy'])) {
            throw TermException::invalidTaxonomy($termData['taxonomy'] ?? '');
        }
    }

    public static function validateId(int $termId): void
    {
        if (!term_exists($termId)) {
            throw TermException::notFound($termId);
        }
    }
}
