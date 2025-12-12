<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Validator;

use Vigihdev\WpCliEntityCommand\Exceptions\PostException;

final class PostValidator
{
    /**
     * Validate post data before creation/update
     */
    public static function validate(array $postData): void
    {
        if (empty($postData['post_title'])) {
            throw new PostException('Judul post tidak boleh kosong');
        }

        if (empty($postData['post_content'])) {
            throw new PostException('Konten post tidak boleh kosong');
        }

        if (!empty($postData['post_type']) && !post_type_exists($postData['post_type'])) {
            throw PostException::invalidPostType($postData['post_type']);
        }
    }

    /**
     * Validate post ID exists
     */
    public static function validateId(int $postId): void
    {
        if (!get_post($postId)) {
            throw PostException::notFound($postId);
        }
    }
}
