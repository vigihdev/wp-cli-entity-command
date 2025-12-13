<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use Throwable;
use Vigihdev\WpCliEntityCommand\Validator\PostValidator;
use Vigihdev\WpCliEntityCommand\WP_CLI\Post_Base_Command;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI;


final class Get_Post_Command extends Post_Base_Command
{

    public function __invoke(array $args, array $assoc_args): void
    {
        $arg = isset($args[0]) ? (int) $args[0] : 0;
        $io = new CliStyle();

        try {
            PostValidator::validate($arg)->mustExist();
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }

        WP_CLI::success(
            sprintf('Execute basic command from %s', Get_Post_Command::class)
        );
    }

    private function preProcess() {}
    private function process() {}
}
