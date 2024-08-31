# Exception Toolkit

🧰 Provides a set of tools to handle exceptions in PHP applications.

[![Build Status](https://img.shields.io/github/actions/workflow/status/phphd/exception-toolkit/ci.yaml?branch=main)](https://github.com/phphd/exception-toolkit/actions?query=branch%3Amain)
[![Codecov](https://codecov.io/gh/phphd/exception-toolkit/graph/badge.svg?token=GZRXWYT55Z)](https://codecov.io/gh/phphd/exception-toolkit)
[![Packagist Downloads](https://img.shields.io/packagist/dt/phphd/exception-toolkit.svg)](https://packagist.org/packages/phphd/exception-toolkit)
[![Licence](https://img.shields.io/github/license/phphd/exception-toolkit.svg)](https://github.com/phphd/exception-toolkit/blob/main/LICENSE)

## Installation 📥

1. Install via composer

    ```sh
    composer require phphd/exception-toolkit
    ```

2. In case you are using symfony, enable the bundle in the `bundles.php`

    ```php
    PhPhD\ExceptionToolkit\Bundle\PhdExceptionToolkitBundle::class => ['all' => true],
    ```

## Provided tools ⚙️

### Exception Unwrapper

Allows you to unwrap composite exceptions and get the atomic errors you are interested in:

```php
use PhPhD\ExceptionToolkit\Unwrapper\ExceptionUnwrapper;

/** @var ExceptionUnwrapper $unwrapper */
$unwrapper = getUnwrapper(); 

$compositeException = new CompositeException([
    new InvalidEmailException(),
    new CompositeException([
        new InvalidPasswordException(),
    ]),
]);

[$emailError, $passwordError] = $unwrapper->unwrap($compositeException);
```

In this example, errors were retrieved from composite exceptions: `$emailError` will be an
instance of `InvalidEmailException` and `$passwordError` will be an
instance of `InvalidPasswordException` that were wrapped in the composite exception.

#### Symfony integration

In symfony application you could use ExceptionUnwrapper service:

```php 
public function __construct(
    #[Autowire('@phd_exception_toolkit.exception_unwrapper')] 
    private ExceptionUnwrapper $exceptionUnwrapper,
) {}
```

This will provide you with full stack of defined unwrappers bundled into a single instance.

> If you want to define custom unwrapper,
> you should decorate `phd_exception_toolkit.exception_unwrapper.stack`
> service. 

#### Built-in unwrappers

##### Messenger

If you are using symfony messenger, `Symfony\Component\Messenger\Exception\WrappedExceptionsInterface`
will be unwrapped automatically.

##### Amp

If you are using Amp, `Amp\CompositeException` will be unwrapped automatically. 

