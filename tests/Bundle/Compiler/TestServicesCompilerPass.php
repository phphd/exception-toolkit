<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Tests\Bundle\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class TestServicesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->getDefinition('phd_exception_toolkit.exception_unwrapper')->setPublic(true);
    }
}
