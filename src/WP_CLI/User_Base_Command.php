<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\WP_CLI;

use Vigihdev\WpCliModels\Contracts\Fields\FieldInterface;
use Vigihdev\WpCliModels\Exceptions\Handler\{HandlerExceptionInterface, WpCliExceptionHandler};
use Vigihdev\WpCliModels\Fields\UserField;
use WP_CLI_Command;

abstract class User_Base_Command extends WP_CLI_Command
{
    protected string $fields = '';

    protected HandlerExceptionInterface $exceptionHandler;

    protected FieldInterface $userField;

    /**
     * @param string $name Command name.
     */
    public function __construct(
        protected string $name
    ) {
        parent::__construct();
        $this->exceptionHandler = new WpCliExceptionHandler();
    }

    protected function instanceFields(): self
    {
        $this->userField = new UserField(fields: $this->fields);
        return $this;
    }
}
