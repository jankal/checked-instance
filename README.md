# Checked Instances

[![Coverage Status](https://coveralls.io/repos/github/jankal/checked-instance/badge.svg?branch=master)](https://coveralls.io/github/jankal/checked-instance?branch=master)
[![Build Status](https://travis-ci.org/jankal/checked-instance.svg?branch=master)](https://travis-ci.org/jankal/checked-instance)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jankal/checked-instance/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jankal/checked-instance/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/jankal/checked-instance/v/stable)](https://packagist.org/packages/jankal/checked-instance)
[![License](https://poser.pugx.org/jankal/checked-instance/license)](https://packagist.org/packages/jankal/checked-instance)
[![Total Downloads](https://poser.pugx.org/jankal/checked-instance/downloads)](https://packagist.org/packages/jankal/checked-instance)
[![Latest Unstable Version](https://poser.pugx.org/jankal/checked-instance/v/unstable)](https://packagist.org/packages/jankal/checked-instance)
[![composer.lock](https://poser.pugx.org/jankal/checked-instance/composerlock)](https://packagist.org/packages/jankal/checked-instance)

Create instances without the hassle of setting many constructor arguments.

`CheckedInstance\Factory` will create an instance of a given class implementing the `CheckedInstance\InstanceInterface`.
## Factory
### Create a newiInstance of `TestClass`
```php
<?php
$factory = CheckedInstance\Factory::for(TestClass::class);
$object = $factory->make();
```
OR
```php
<?php
$factory = new CheckedInstance\Factory();
$factory->as(TestClass::class);
$object = $factory->make();
```
OR
```php
<?php
$factory = new CheckedInstance\Factory();
$object = $factory->make(TestClass::class);
```

### Create a new instance of `TestClass` with parameters
```php
<?php
$factory = new CheckedInstance\Factory();
$factory->with('authKey', '84746afg7u789h2');
$object = $factory->make();
```
Other options according to the examples above!

### Inject parameters from an `\Psr\Container\ContainerInterface`
```php
<?php
/** @var $c \Psr\Container\ContainerInterface */
CheckedInstance\Factory::container($c);
$factory = new CheckedInstance\Factory();
$object = $factory->make();
```
One can also use a prefix that is prepended in front of the actual parameter
```php
<?php
/** @var $c \Psr\Container\ContainerInterface */
CheckedInstance\Factory::container($c);
$factory = CheckedInstance\Factory::prefix('test.');
$object = $factory->make();
```

## Instance
A class which can be instantiated by the `CheckedInstance\Factory` needs to implement the `CheckedInstance\InstanceInterface`.
But it may also inherit from `CheckedInstance\Instance`.
```php
<?php
class TestInstance extends CheckedInstance\Instance {
    protected $required = [
        'authKey'
    ];
}
```
With this the Instance will only be made successfully if the `authKey` is set like `CheckedInstance\Factory->with('authKey', <-value->)`
