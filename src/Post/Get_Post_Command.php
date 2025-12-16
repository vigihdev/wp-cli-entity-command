<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use Throwable;
use Vigihdev\WpCliEntityCommand\WP_CLI\Post_Base_Command;
use Vigihdev\WpCliModels\DTOs\Entities\Post\PostEntityDto;
use Vigihdev\WpCliModels\Entities\PostEntity;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\PostValidator;

final class Get_Post_Command extends Post_Base_Command
{

    private PostEntityDto $post;

    public function __construct()
    {
        parent::__construct(name: 'post:get');
    }

    /**
     * Get post by ID
     *
     * ## OPTIONS
     *
     * <post-id>
     * : The ID of the post to get.
     *
     * ## EXAMPLES
     *
     *     # Get post with ID 123
     *     $ wp post:get 123
     *
     * @param array $args
     * @param array $assoc_args
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $arg = isset($args[0]) ? (int) $args[0] : 0;
        $io = new CliStyle();

        try {
            PostValidator::validate($arg)->mustBeExist();
            $this->post = PostEntity::get($arg);
            $this->process($io);
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    private function process(CliStyle $io)
    {
        $post = $this->post;
        $io->log('');
        $io->line(
            sprintf("🔥 %s", $io->textGreen('Post Details'))
        );

        $io->hr('-', 75);
        $io->listLabel(
            [(string) $post->getId(), $post->getTitle(), $post->getStatus(), $post->getType()],
            ['ID', 'Title', 'Status', 'Type']
        );
        $io->hr('-', 75);
    }
}
