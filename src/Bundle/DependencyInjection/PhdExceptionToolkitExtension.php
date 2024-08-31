<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Bundle\DependencyInjection;

use Amp\CompositeException as AmpCompositeException;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Messenger\Exception\WrappedExceptionsInterface as MessengerCompositeException;

use function class_exists;
use function interface_exists;

final class PhdExceptionToolkitExtension extends Extension
{
    public const ALIAS = 'phd_exception_toolkit';

    /**
     * @param array<array-key,mixed> $configs
     *
     * @override
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        /** @var ?string $env */
        $env = $container->getParameter('kernel.environment');

        $loader = new YamlFileLoader($container, new FileLocator(), $env);
        $loader->load(__DIR__.'/../../Unwrapper/services.yaml');

        if (class_exists(AmpCompositeException::class)) {
            $loader->load(__DIR__.'/../../Unwrapper/Amp/services.yaml');
        }

        if (interface_exists(MessengerCompositeException::class)) {
            $loader->load(__DIR__.'/../../Unwrapper/Messenger/services.yaml');
        }
    }

    /** @override */
    public function getAlias(): string
    {
        return self::ALIAS;
    }
}
