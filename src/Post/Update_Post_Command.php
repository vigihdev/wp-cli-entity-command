<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use Throwable;
use Vigihdev\WpCliEntityCommand\Validator\PostValidator;
use Vigihdev\WpCliEntityCommand\WP_CLI\Post_Base_Command;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;
use WP_CLI_Command;

final class Update_Post_Command extends Post_Base_Command
{

    public function __construct()
    {
        parent::__construct(name: 'post:update');
    }

    /**
     *
     * Update post by ID
     *
     * ## OPTIONS
     *
     * <post-id>
     * : The ID of the post to update.
     *
     * ## EXAMPLES
     *
     *     # Update post with ID 123
     *     $ wp post:update 123
     *
     * @param array $args
     * @param array $assoc_args
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {

        $postId = isset($args[0]) ? (int) $args[0] : 0;
        $io = new CliStyle();
        try {
            PostValidator::validate($postId)->mustExist();
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    private function prePprocess() {}
    private function process() {}
}
