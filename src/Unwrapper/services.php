<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Unwrapper\Amp;

use PhPhD\ExceptionToolkit\Unwrapper\ExceptionUnwrapper;
use PhPhD\ExceptionToolkit\Unwrapper\PassThroughExceptionUnwrapper;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->alias('phd_exception_toolkit.exception_unwrapper', ExceptionUnwrapper::class);

    // Stack is used to create a chain of decorated services.
    // When adding new unwrapper, you should decorate this service:
    $services
        ->set('phd_exception_toolkit.exception_unwrapper.stack', PassThroughExceptionUnwrapper::class)
    ;
};
