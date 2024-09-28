<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Unwrapper\Amp;

use Amp\CompositeException;
use PhPhD\ExceptionToolkit\Unwrapper\ExceptionUnwrapper;
use Throwable;

use function array_map;
use function array_merge;
use function array_values;

final class AmpExceptionUnwrapper implements ExceptionUnwrapper
{
    public function __construct(
        private readonly ExceptionUnwrapper $innerUnwrapper,
        private readonly ExceptionUnwrapper $outerUnwrapper,
    ) {
    }

    public function unwrap(Throwable $exception): array
    {
        if (!$exception instanceof CompositeException) {
            return $this->innerUnwrapper->unwrap($exception);
        }

        $wrappedExceptions = array_values($exception->getReasons());

        $unwrapped = array_map(
            $this->outerUnwrapper->unwrap(...),
            $wrappedExceptions,
        );

        return array_merge(...$unwrapped);
    }
}
