<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_ObjectGraphConstructorTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->ogc = new \Vidola\Util\ObjectGraphConstructor();
	}

	/**
	 * @test
	 */
	public function createsInstanceFromClassName()
	{
		$this->assertEquals(
			new \Vidola\UnitTests\Support\ClassWithoutDependencies(),
			$this->ogc->getInstance('\\Vidola\\UnitTests\\Support\\ClassWithoutDependencies')
		);
	}

	/**
	 * @test
	 * @todo this should be configurable
	 */
	public function theSameInstanceIsReturned()
	{
		$this->assertSame(
			$this->ogc->getInstance('\\Vidola\\UnitTests\\Support\\ClassWithoutDependencies'),
			$this->ogc->getInstance('\\Vidola\\UnitTests\\Support\\ClassWithoutDependencies')
		);
	}

	/**
	 * @test
	 */
	public function aClassDependencyIsInjectedAutomatically()
	{
		$this->assertEquals(
			new \Vidola\UnitTests\Support\ClassWithDependency(
				new \Vidola\UnitTests\Support\ClassWithoutDependencies()
			),
			$this->ogc->getInstance('\\Vidola\\UnitTests\\Support\\ClassWithDependency')
		);
	}

	/**
	 * @test
	 */
	public function deeperNestedDependenciesAreInjectedAutomatically()
	{
		$this->assertEquals(
			new \Vidola\UnitTests\Support\ClassWithDependencyDependency(
				new \Vidola\UnitTests\Support\ClassWithDependency(
					new \Vidola\UnitTests\Support\ClassWithoutDependencies()
				)
			),
			$this->ogc->getInstance(
				'\\Vidola\\UnitTests\\Support\\ClassWithDependencyDependency'
			)
		);
	}

	/**
	 * @test
	 */
	public function anInterfaceDependencyIsLoadedDependingOnSpecifiedImplementation()
	{
		$this->ogc->willUse('\\Vidola\\UnitTests\\Support\\ClassImplementingAnInterface');

		$this->assertEquals(
			new \Vidola\UnitTests\Support\ClassWithInterfaceDependency(
				new \Vidola\UnitTests\Support\ClassImplementingAnInterface()
			),
			$this->ogc->getInstance(
				'\\Vidola\\UnitTests\\Support\\ClassWithInterfaceDependency'
			)
		);
	}

	/**
	 * @test
	 */
	public function usesAlreadyInstantiatedClassImplementingSpecifiedInterfaceDependency()
	{
		$this->ogc->willUse('\\Vidola\\UnitTests\\Support\\ClassImplementingAnInterface');
		$this->ogc->getInstance('\\Vidola\\UnitTests\\Support\\Class2ImplementingAnInterface');

		$this->assertEquals(
			new \Vidola\UnitTests\Support\ClassWithInterfaceDependency(
				new \Vidola\UnitTests\Support\ClassImplementingAnInterface()
			),
			$this->ogc->getInstance(
				'\\Vidola\\UnitTests\\Support\\ClassWithInterfaceDependency'
			)
		);
	}

	/**
	 * @test
	 */
	public function primitiveValuesCanBeGivenForConstructors()
	{
		$this->ogc->bind(
			array('5'),
			'__construct',
			'\\Vidola\\UnitTests\\Support\\ClassWithConstructorId'
		);
		$obj = $this->ogc->getInstance(
			'\\Vidola\\UnitTests\\Support\\ClassWithConstructorId'
		);

		$this->assertEquals('5', $obj->getId());
	}

	/**
	 * @test
	 */
	public function primitiveValuesCanBeGivenForMethods()
	{
		$this->ogc->bind(
			array('5'),
			'__construct',
			'\\Vidola\\UnitTests\\Support\\ClassWithConstructorId'
		);
		$this->ogc->bind(
			array('6'),
			'setId',
			'\\Vidola\\UnitTests\\Support\\ClassWithConstructorId'
		);
		$obj = $this->ogc->getInstance(
			'\\Vidola\\UnitTests\\Support\\ClassWithConstructorId'
		);

		$this->assertEquals('6', $obj->getId());
	}
}