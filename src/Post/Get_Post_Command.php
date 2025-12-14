<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use Throwable;
use Vigihdev\WpCliEntityCommand\Validator\PostValidator;
use Vigihdev\WpCliEntityCommand\WP_CLI\Post_Base_Command;
use Vigihdev\WpCliModels\Entities\PostEntity;
use Vigihdev\WpCliModels\UI\CliStyle;


final class Get_Post_Command extends Post_Base_Command
{

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
            PostValidator::validate($arg)->mustExist();
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }

        $post = PostEntity::get($arg);

        $io->log('');
        $io->line(
            sprintf("🔥 %s", $io->textGreen('Post Details'))
        );

        $io->hr('-', 75);
        $this->line('ID', (string) $post->getId());
        $this->line('Title', $post->getTitle());
        $this->line('Status', $post->getStatus());
        $this->line('Type', $post->getType());
        $io->hr('-', 75);
    }

    private function line(string $lsbel, string $value)
    {
        $io = new CliStyle();
        $io->line(
            sprintf("🏮 %s : %s", $io->highlightText($lsbel), $io->textGreen($value, '%g'))
        );
    }

    private function preProcess() {}
    private function process() {}
}
