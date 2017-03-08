<?php
namespace Tests;

use CheckedInstance\InstanceCorruptException;
use PHPUnit\Framework\TestCase;

/**
 * Class InstanceTest
 * @package Tests
 */
class InstanceTest extends TestCase
{
    public function testCheckThrowsExceptionOnMissingRequiredVar()
    {
        $instance = new TestingRequiredInstance();
        $this->expectException(InstanceCorruptException::class);
        $instance->check();
    }

    public function testGetRquired()
    {
        $this->assertEquals([
            'subscriptionKey'
        ], TestingRequiredInstance::getRequired());
    }

    public function testSetCheck()
    {
        $instance = new TestingRequiredInstance();
        $instance->set('subscriptionKey', '454684');
        $this->assertTrue($instance->check());
        unset($instance);

        $instance = new TestingImplementationInjectionInstance();
        $instance->set('ins', new TestingNonInstance());
        $this->assertTrue($instance->check());
        unset($instance);

        $instance = new TestingImplementationMixedInjectionInstance();
        $instance->set('ins', new TestingNonInstance());
        $instance->set('subscriptionKey', '4578964113');
        $this->assertTrue($instance->check());
    }
}
