<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\WP_CLI;

use WP_CLI_Command;

abstract class Menu_Base_Command extends WP_CLI_Command
{

    public function __construct(
        protected string $name
    ) {
        return parent::__construct();
    }
}
