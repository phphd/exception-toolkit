<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Unwrapper\Tests;

use PhPhD\ExceptionToolkit\Bundle\Tests\BundleTestCase;
use PhPhD\ExceptionToolkit\Unwrapper\Amp\AmpExceptionUnwrapper;
use PhPhD\ExceptionToolkit\Unwrapper\ExceptionUnwrapper;
use PhPhD\ExceptionToolkit\Unwrapper\Messenger\MessengerExceptionUnwrapper;
use Symfony\Component\VarExporter\LazyObjectInterface;

/**
 * @covers \PhPhD\ExceptionToolkit\Bundle\PhdExceptionToolkitBundle
 * @covers \PhPhD\ExceptionToolkit\Bundle\DependencyInjection\PhdExceptionToolkitExtension
 *
 * @internal
 */
final class ExceptionUnwrapperServiceTest extends BundleTestCase
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
        $exceptionUnwrapper = self::getContainer()->get(ExceptionUnwrapper::class);
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
