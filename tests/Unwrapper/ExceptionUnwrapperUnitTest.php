<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Tests\Unwrapper;

use Amp\CompositeException;
use Exception;
use PhPhD\ExceptionToolkit\Unwrapper\Amp\AmpExceptionUnwrapper;
use PhPhD\ExceptionToolkit\Unwrapper\ExceptionUnwrapper;
use PhPhD\ExceptionToolkit\Unwrapper\Messenger\MessengerExceptionUnwrapper;
use PhPhD\ExceptionToolkit\Unwrapper\PassThroughExceptionUnwrapper;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

/**
 * @covers \PhPhD\ExceptionToolkit\Unwrapper\PassThroughExceptionUnwrapper
 * @covers \PhPhD\ExceptionToolkit\Unwrapper\Amp\AmpExceptionUnwrapper
 * @covers \PhPhD\ExceptionToolkit\Unwrapper\Messenger\MessengerExceptionUnwrapper
 *
 * @internal
 */
final class ExceptionUnwrapperUnitTest extends TestCase
{
    private ExceptionUnwrapper $exceptionUnwrapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exceptionUnwrapper = self::createStub(ExceptionUnwrapper::class);

        $stackUnwrapper = new MessengerExceptionUnwrapper(
            new AmpExceptionUnwrapper(
                new PassThroughExceptionUnwrapper(),
                $this->exceptionUnwrapper,
            ),
            $this->exceptionUnwrapper,
        );

        $this->exceptionUnwrapper->method('unwrap')
            ->willReturnCallback(static fn (Throwable $exception): array => $stackUnwrapper->unwrap($exception))
        ;
    }

    public function testAtomicExceptionIsNotUnwrapped(): void
    {
        $exception = new Exception('Atomic exception');

        $unwrapped = $this->exceptionUnwrapper->unwrap($exception);

        self::assertSame([$exception], $unwrapped);
    }

    public function testPreviousExceptionIsNotUnwrapped(): void
    {
        $previous = new Exception('Previous exception');
        $exception = new Exception('Atomic exception', 0, $previous);

        $unwrapped = $this->exceptionUnwrapper->unwrap($exception);

        self::assertSame([$exception], $unwrapped);
    }

    public function testCompositeExceptionIsUnwrapped(): void
    {
        $exception1 = new Exception('First exception');
        $exception2 = new Exception('Second exception');

        $compositeException = new CompositeException(
            [
                new HandlerFailedException(
                    Envelope::wrap(new stdClass()),
                    [
                        'first' => $exception1,
                        new CompositeException([
                            'second' => $exception2,
                        ]),
                    ],
                ),
            ],
        );

        $unwrapped = $this->exceptionUnwrapper->unwrap($compositeException);

        self::assertSame([$exception1, $exception2], $unwrapped);
    }
}
