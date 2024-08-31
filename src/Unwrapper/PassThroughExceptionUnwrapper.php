<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Unwrapper;

use Throwable;

final class PassThroughExceptionUnwrapper implements ExceptionUnwrapper
{
    /** @return array{Throwable} */
    public function unwrap(Throwable $exception): array
    {
        return [$exception];
    }
}
