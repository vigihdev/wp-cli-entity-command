<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\WP_CLI;

use Vigihdev\WpCliModels\Exceptions\Handler\HandlerExceptionInterface;
use Vigihdev\WpCliModels\Exceptions\Handler\WpCliExceptionHandler;
use WP_CLI_Command;

abstract class Taxonomy_Base_Command extends WP_CLI_Command
{
    protected HandlerExceptionInterface $exceptionHandler;
    public function __construct(
        protected string $name
    ) {
        parent::__construct();
        $this->exceptionHandler = new WpCliExceptionHandler();
    }
}
