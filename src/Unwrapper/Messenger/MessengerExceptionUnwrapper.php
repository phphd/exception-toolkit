<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Unwrapper\Messenger;

use PhPhD\ExceptionToolkit\Unwrapper\ExceptionUnwrapper;
use Symfony\Component\Messenger\Exception\WrappedExceptionsInterface;
use Throwable;

use function array_map;
use function array_merge;

final class MessengerExceptionUnwrapper implements ExceptionUnwrapper
{
    public function __construct(
        private readonly ExceptionUnwrapper $innerUnwrapper,
        private readonly ExceptionUnwrapper $outerUnwrapper,
    ) {
    }

    public function unwrap(Throwable $exception): array
    {
        if (!$exception instanceof WrappedExceptionsInterface) {
            return $this->innerUnwrapper->unwrap($exception);
        }

        /** @var non-empty-array<Throwable> $wrappedExceptions */
        $wrappedExceptions = $exception->getWrappedExceptions();

        $unwrappedExceptions = array_map(
            $this->outerUnwrapper->unwrap(...),
            $wrappedExceptions,
        );

        return array_merge(...$unwrappedExceptions);
    }
}
