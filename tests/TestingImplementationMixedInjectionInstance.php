<?php
namespace Tests;

use CheckedInstance\Instance;

/**
 * Class TestingImplementationMixedInjectionInstance
 * @package Tests
 */
class TestingImplementationMixedInjectionInstance extends Instance
{
    /**
     * {@inheritdoc}
     */
    protected $required = [
        TestingNonInstance::class => 'ins',
        'subscriptionKey'
    ];

    /**
     * @return TestingNonInstance
     */
    public function getIns() : TestingNonInstance
    {
        return $this->get('ins');
    }

    /**
     * @return string
     */
    public function getSubscriptionKey() : string
    {
        return $this->get('subscriptionKey');
    }
}
