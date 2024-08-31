<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Unwrapper;

use Throwable;

/** @api */
interface ExceptionUnwrapper
{
    /** @return non-empty-list<Throwable> */
    public function unwrap(Throwable $exception): array;
}
