<?php
namespace Tests;

use CheckedInstance\Instance;

/**
 * Class TestingRequiredInstance
 * @package Tests
 */
class TestingRequiredInstance extends Instance
{
    /**
     * {@inheritDoc}
     */
    protected $required = [
        'subscriptionKey'
    ];
}
