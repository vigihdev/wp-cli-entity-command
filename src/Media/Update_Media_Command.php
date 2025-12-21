<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Media;

use Vigihdev\WpCliEntityCommand\WP_CLI\Media_Base_Command;
use WP_CLI;
use WP_CLI_Command;

final class Update_Media_Command extends Media_Base_Command
{

    public function __construct()
    {
        parent::__construct(name: 'media:update');
    }

    public function __invoke(array $args, array $assoc_args): void
    {
        WP_CLI::success(
            sprintf('Execute basic command from %s', self::class)
        );
    }
}
