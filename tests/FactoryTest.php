<?php
namespace Tests;

use CheckedInstance\Factory;
use CheckedInstance\FactoryFailureException;
use CheckedInstance\InstanceCorruptException;
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

    public function testNotSettingRequiredThrowsExceptionOnMake()
    {
        $factory = Factory::for(TestingRequiredInstance::class);
        $this->expectException(InstanceCorruptException::class);
        $factory->make();
    }

    public function testContainerInjection()
    {
        $c = new TestContainer();
        $c->set('subscriptionKey', '454657645');
        Factory::container($c);
        $factory = Factory::for (TestingRequiredInstance::class);
        $instance = $factory->make();
        $this->assertInstanceOf(TestingRequiredInstance::class, $instance);
    }

    public function testContainerInjectionWithPrefix()
    {
        $c = new TestContainer();
        $c->set('reqInst.subscriptionKey', '454657645');
        Factory::container($c);
        $factory = Factory::prefix('reqInst.');
        $factory->as(TestingRequiredInstance::class);
        $instance = $factory->make();
        $this->assertInstanceOf(TestingRequiredInstance::class, $instance);
    }

    public function testSettingSubjectClassThroughAs()
    {
        $factory = new Factory();
        $factory->as(TestingInstance::class);
        $this->assertEquals(TestingInstance::class, $factory->getFor());
        $instance = $factory->make();
        $this->assertInstanceOf(TestingInstance::class, $instance);
    }

    public function testMethodChaining()
    {
        $factory = new Factory();
        $this->assertInstanceOf(Factory::class, $factory->as(TestingInstance::class));
        $this->assertInstanceOf(Factory::class, $factory->with('foo', 'baaar'));
    }

    public function testImplementationInjection()
    {
        $c = new TestContainer();
        $c->set(TestingNonInstance::class, new TestingNonInstance());
        Factory::container($c);
        $factory = Factory::for (TestingImplementationInjectionInstance::class);
        $instance = $factory->make();
        $this->assertInstanceOf(TestingImplementationInjectionInstance::class, $instance);
        $this->assertInstanceOf(TestingNonInstance::class, $instance->getIns());
    }

    public function testMixedRequiresInstanceMixedContainer()
    {
        $c = new TestContainer();
        $c->set(TestingNonInstance::class, new TestingNonInstance());
        Factory::container($c);
        $factory = Factory::for (TestingImplementationMixedInjectionInstance::class);
        $factory->with('subscriptionKey', '454657645');
        $instance = $factory->make();
        $this->assertInstanceOf(TestingNonInstance::class, $instance->getIns());
        $this->assertEquals('454657645', $instance->getSubscriptionKey());
        $this->assertTrue($instance->check());
    }

    public function testMixedRequiresInstanceContainerOnly()
    {
        $c = new TestContainer();
        $c->set(TestingNonInstance::class, new TestingNonInstance());
        $c->set('subscriptionKey', '454657645');
        Factory::container($c);
        $factory = Factory::for (TestingImplementationMixedInjectionInstance::class);
        $instance = $factory->make();
        $this->assertInstanceOf(TestingNonInstance::class, $instance->getIns());
        $this->assertEquals('454657645', $instance->getSubscriptionKey());
        $this->assertTrue($instance->check());
    }

    public function testMixedRequiresInstanceSet()
    {
        $factory = Factory::for (TestingImplementationMixedInjectionInstance::class);
        $factory->with('ins', new TestingNonInstance());
        $factory->with('subscriptionKey', '454657645');
        $instance = $factory->make();
        $this->assertInstanceOf(TestingNonInstance::class, $instance->getIns());
        $this->assertEquals('454657645', $instance->getSubscriptionKey());
        $this->assertTrue($instance->check());
    }

    public function testRequiredImplementationInstantiation()
    {
        $factory = Factory::for (TestingImplementationInjectionInstance::class);
        $instance = $factory->make();
        $this->assertInstanceOf(TestingImplementationInjectionInstance::class, $instance);
        $this->assertInstanceOf(TestingNonInstance::class, $instance->getIns());
    }

    public function testRequiredImplementationInstantiationOnMixedWithContainer()
    {
        $c = new TestContainer();
        $c->set('subscriptionKey', '454657645');
        Factory::container($c);
        $factory = Factory::for (TestingImplementationMixedInjectionInstance::class);
        $instance = $factory->make();
        $this->assertInstanceOf(TestingImplementationMixedInjectionInstance::class, $instance);
        $this->assertInstanceOf(TestingNonInstance::class, $instance->getIns());
    }

    public function testRequiredImplementationInstantiationOnMixed()
    {
        $factory = Factory::for (TestingImplementationMixedInjectionInstance::class);
        $factory->with('subscriptionKey', '454657645');
        $instance = $factory->make();
        $this->assertInstanceOf(TestingImplementationMixedInjectionInstance::class, $instance);
        $this->assertInstanceOf(TestingNonInstance::class, $instance->getIns());
    }

    public function testNotInstatiateClassWithRequiredConstructorParameters()
    {
        $factory = Factory::for (TestingImplementationInjectionInstanceWithConstructDependency::class);
        $this->expectException(InstanceCorruptException::class);
        $instance = $factory->make();
    }
}
