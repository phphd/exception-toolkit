<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Unwrapper\Messenger;

use PhPhD\ExceptionToolkit\Unwrapper\ExceptionUnwrapper;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\Exception\WrappedExceptionsInterface as MessengerCompositeException;

use function interface_exists;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    if (!interface_exists(MessengerCompositeException::class)) {
        return;
    }

    $services = $containerConfigurator->services();

    $services
        ->set('phd_exception_toolkit.exception_unwrapper.messenger', MessengerExceptionUnwrapper::class)
        ->decorate('phd_exception_toolkit.exception_unwrapper.stack')
        ->args([
            service('.inner'),
            service(ExceptionUnwrapper::class),
        ])
    ;
};
