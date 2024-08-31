<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Tests\Bundle;

use Nyholm\BundleTest\TestKernel;
use PhPhD\ExceptionToolkit\Bundle\PhdExceptionToolkitBundle;
use PhPhD\ExceptionToolkit\Tests\Bundle\Compiler\TestServicesCompilerPass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class BundleTestCase extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    /** @param array<array-key,mixed> $options */
    protected static function createKernel(array $options = []): KernelInterface
    {
        /** @var TestKernel $kernel */
        $kernel = parent::createKernel($options);
        $kernel->addTestBundle(PhdExceptionToolkitBundle::class);
        $kernel->addTestCompilerPass(new TestServicesCompilerPass());

        return $kernel;
    }
}
