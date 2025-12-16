<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\WP_CLI;

use Vigihdev\WpCliModels\Exceptions\Handler\{HandlerExceptionInterface, WpCliExceptionHandler};
use WP_CLI_Command;

abstract class Post_Base_Command extends WP_CLI_Command
{

    protected HandlerExceptionInterface $exceptionHandler;
    public function __construct(
        protected string $name
    ) {

        parent::__construct();
        $this->exceptionHandler = new WpCliExceptionHandler();
    }
}
