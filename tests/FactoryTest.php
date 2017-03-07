<?php
namespace Tests;

use CheckedInstance\Factory;
use CheckedInstance\FactoryFailureException;
use PHPUnit\Framework\TestCase;

/**
 * Class FactoryTest
 * @package Tests
 */
class FactoryTest extends TestCase
{
    public function testForReturnsInstanceOfFactory()
    {
        $returnValue = Factory::for(TestingInstance::class);
        $this->assertInstanceOf(Factory::class, $returnValue);
    }

    public function testWithReturnsInstanceOfFactory()
    {
        $factory = new Factory();
        $returnValue = $factory->with('foo', 'baaar');
        $this->assertInstanceOf(Factory::class, $returnValue);
    }

    public function testWithSetsVars()
    {
        $factory = new Factory();
        $factory->with('foo', 'baaar');
        $this->assertEquals(['foo' => 'baaar'], $factory->getVars());
    }

    public function testForSetsClassname()
    {
        $factory = Factory::for(TestingInstance::class);
        $this->assertEquals(TestingInstance::class, $factory->getFor());
    }

    public function testForMakeReturnsObject()
    {
        $factory = Factory::for(TestingInstance::class);
        $instance = $factory->make();
        $this->assertInstanceOf(TestingInstance::class, $instance);
    }

    public function testMakeReturnsObject()
    {
        $factory = new Factory();
        $instance = $factory->make(TestingInstance::class);
        $this->assertInstanceOf(TestingInstance::class, $instance);
    }

    public function testMakeWithReturnsCheckedInstance()
    {
        $factory = Factory::for(TestingRequiredInstance::class);
        $factory->with('subscriptionKey', '454657645');
        $instance = $factory->make();
        $this->assertInstanceOf(TestingRequiredInstance::class, $instance);
    }

    public function testNotSettingForThrowsExceptionOnMake()
    {
        $factory = new Factory();
        $this->expectException(FactoryFailureException::class);
        $factory->make();
    }

    public function testUsingUntypedClassThrowsExceptionOnMake()
    {
        $factory = Factory::for(TestingNonInstance::class);
        $this->expectException(FactoryFailureException::class);
        $factory->make();
    }
}
