<?php
namespace Tests;

use CheckedInstance\Instance;

/**
 * Class TestingImplementationInjectionInstanceWithConstructDependency
 * @package Tests
 */
class TestingImplementationInjectionInstanceWithConstructDependency extends Instance
{
    /**
     * {@inheritdoc}
     */
    protected $required = [
        TestingConstructorInstance::class => 'ins'
    ];
}
