<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Unwrapper\Amp;

use Amp\CompositeException;
use PhPhD\ExceptionToolkit\Unwrapper\ExceptionUnwrapper;
use Throwable;

use function array_map;
use function array_merge;

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

        $unwrapped = array_map(
            $this->outerUnwrapper->unwrap(...),
            $exception->getReasons(),
        );

        return array_merge(...$unwrapped);
    }
}
