<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Tests\Bundle;

use PhPhD\ExceptionToolkit\Unwrapper\Amp\AmpExceptionUnwrapper;
use PhPhD\ExceptionToolkit\Unwrapper\Messenger\MessengerExceptionUnwrapper;
use Symfony\Component\VarExporter\LazyObjectInterface;

/**
 * @covers \PhPhD\ExceptionToolkit\Bundle\PhdExceptionToolkitBundle
 * @covers \PhPhD\ExceptionToolkit\Bundle\DependencyInjection\PhdExceptionToolkitExtension
 *
 * @internal
 */
final class DependencyInjectionIntegrationTest extends BundleTestCase
{
    public function testServiceDefinitions(): void
    {
        $this->checkExceptionUnwrapper();
    }

    private function checkExceptionUnwrapper(): void
    {
        $this->checkTopmostUnwrapper();
        $this->checkAmpUnwrapper();
        $this->checkMessengerUnwrapper();
    }

    private function checkTopmostUnwrapper(): void
    {
        /** @var LazyObjectInterface|mixed $exceptionUnwrapper */
        $exceptionUnwrapper = self::getContainer()->get('phd_exception_toolkit.exception_unwrapper');
        self::assertInstanceOf(LazyObjectInterface::class, $exceptionUnwrapper);
        self::assertFalse($exceptionUnwrapper->isLazyObjectInitialized());
        $topmostUnwrapper = $exceptionUnwrapper->initializeLazyObject();

        $stackUnwrapper = self::getContainer()->get('phd_exception_toolkit.exception_unwrapper.stack');
        self::assertInstanceOf(MessengerExceptionUnwrapper::class, $stackUnwrapper);
        self::assertSame($topmostUnwrapper, $stackUnwrapper);
    }

    private function checkAmpUnwrapper(): void
    {
        $ampExceptionUnwrapper = self::getContainer()->get('phd_exception_toolkit.exception_unwrapper.amp');
        self::assertInstanceOf(AmpExceptionUnwrapper::class, $ampExceptionUnwrapper);
    }

    private function checkMessengerUnwrapper(): void
    {
        $messengerExceptionUnwrapper = self::getContainer()->get('phd_exception_toolkit.exception_unwrapper.messenger');
        self::assertInstanceOf(MessengerExceptionUnwrapper::class, $messengerExceptionUnwrapper);
    }
}
