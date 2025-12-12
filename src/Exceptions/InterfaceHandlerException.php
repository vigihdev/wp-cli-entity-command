<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Exceptions;

use Throwable;
use Vigihdev\WpCliModels\UI\CliStyle;

interface InterfaceHandlerException
{

    public function handle(CliStyle $io, Throwable $exception): void;
}
