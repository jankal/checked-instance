<?php
namespace Tests;

use CheckedInstance\Instance;

/**
 * Class TestingImplementationInjectionInstance
 * @package Tests
 */
class TestingImplementationInjectionInstance extends Instance
{
    /**
     * {@inheritDoc}
     */
    protected $required = [
        TestingNonInstance::class => 'ins'
    ];

    /**
     * @return TestingNonInstance
     */
    public function getIns() : TestingNonInstance
    {
        return $this->get('ins');
    }
}
