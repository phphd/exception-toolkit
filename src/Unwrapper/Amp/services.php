<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Unwrapper\Amp;

use Amp\CompositeException as AmpCompositeException;
use PhPhD\ExceptionToolkit\Unwrapper\ExceptionUnwrapper;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function class_exists;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    if (!class_exists(AmpCompositeException::class)) {
        return;
    }

    $services = $containerConfigurator->services();

    $services
        ->set('phd_exception_toolkit.exception_unwrapper.amp', AmpExceptionUnwrapper::class)
        ->decorate('phd_exception_toolkit.exception_unwrapper.stack')
        ->args([
            service('.inner'),
            service(ExceptionUnwrapper::class),
        ])
    ;
};
