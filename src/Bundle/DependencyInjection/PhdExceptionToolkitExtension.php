<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Bundle\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\Compiler\DecoratorServicePass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function array_keys;
use function array_map;

final class PhdExceptionToolkitExtension extends AbstractExtension
{
    public const ALIAS = 'phd_exception_toolkit';

    /**
     * @param array<string,mixed> $parameters required by {@see \Symfony\Component\DependencyInjection\Extension\ExtensionTrait::executeConfiguratorCallback()}:
     *                                        - kernel.environment
     *                                        - kernel.build_dir
     */
    public function getContainer(array $parameters): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $container->setResourceTracking(false);
        $container->getCompilerPassConfig()->setBeforeOptimizationPasses([]);
        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);

        array_map($container->setParameter(...), array_keys($parameters), $parameters); // @phpstan-ignore argument.type

        $this->configureContainer($container);

        return $container;
    }

    /** @internal PhPhD */
    public function configureContainer(ContainerBuilder $container): void
    {
        $container->registerExtension($this);
        $container->loadFromExtension($this->getAlias());

        $container->addCompilerPass(new DecoratorServicePass(), PassConfig::TYPE_OPTIMIZE);
    }

    /**
     * @param array<array-key,mixed> $config
     *
     * @throws Exception
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(__DIR__.'/../../**/services.php');
        $container->import(__DIR__.'/../../**/services.yaml');
    }

    /** @override */
    public function getAlias(): string
    {
        return self::ALIAS;
    }
}
