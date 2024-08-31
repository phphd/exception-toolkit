<?php

declare(strict_types=1);

namespace PhPhD\ExceptionToolkit\Bundle;

use PhPhD\ExceptionToolkit\Bundle\DependencyInjection\PhdExceptionToolkitExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/** @api */
final class PhdExceptionToolkitBundle extends Bundle
{
    /** @override */
    protected function createContainerExtension(): PhdExceptionToolkitExtension
    {
        return new PhdExceptionToolkitExtension();
    }
}
