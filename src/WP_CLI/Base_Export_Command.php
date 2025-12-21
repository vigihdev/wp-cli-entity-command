<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\WP_CLI;

use WP_CLI_Command;

abstract class Base_Export_Command extends WP_CLI_Command
{
    protected const DEFAULT_LIMIT = 20;
    protected int $limit = 0;
    protected int $offset = 0;
    protected ?string $filter = null;
    protected ?string $output = null;
}
