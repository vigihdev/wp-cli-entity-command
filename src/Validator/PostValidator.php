<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Validator;

use Vigihdev\WpCliEntityCommand\Exceptions\PostException;
use WP;
use WP_Post;

final class PostValidator
{
    private ?WP_Post $post = null;
    public function __construct(
        private readonly int $id
    ) {
        $this->post = get_post($this->id);
    }

    public function mustExist(): void
    {
        if (!$this->post) {
            throw PostException::notFound($this->id);
        }
    }

    /**
     * Validate post data before creation/update
     */
    private static function validateed(array $postData): void
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

    public static function validate(int $postId): self
    {
        return new self($postId);
    }
}
