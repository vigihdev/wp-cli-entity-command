<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Exceptions;

use Throwable;
use Vigihdev\WpCliModels\UI\CliStyle;

final class ExceptionHandler implements InterfaceException
{

    public function handle(CliStyle $io, Throwable $exception): void
    {
        $io->error($exception->getMessage());
    }
}
