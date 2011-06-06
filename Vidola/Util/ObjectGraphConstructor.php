<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package
 */
class ObjectGraphConstructor
{
	private $instances = array();

	private $implementingClasses = array();

	public function __construct()
	{
		$this->instances[__CLASS__] = $this;
	}

	/**
	 * Will use class when looking for class implementating an interface.
	 * 
	 * @param string $className
	 */
	public function willUse($className)
	{
		$this->implementingClasses[] = $className;
	}

	/**
	 * Gets an instance of the specified class. The same instance
	 * will be returned.
	 * 
	 * @param string $className
	 */
	public function getInstance($className)
	{
		if (isset($this->instances[$className]))
		{
			return $this->instances[$className];
		}

		return $this->instances[$className] = $this->createClass($className);
	}

	private function createClass($className)
	{
		if (interface_exists($className))
		{
			$className = $this->getImplementingClass($className);
		}

		$reflectionClass = new \ReflectionClass($className);
		$dependencies = array();

		if ($reflectionClass->hasMethod('__construct'))
		{
			$reflectionMethod = $reflectionClass->getMethod('__construct');
			foreach ($reflectionMethod->getParameters() as $reflectionParameter)
			{
				$paramReflectionClass = $reflectionParameter->getClass();
				$paramClassName = $paramReflectionClass->getName();
				$paramObj = $this->getInstance($paramClassName);

				$dependencies[] = $paramObj;
			}
		}

		if (empty($dependencies))
		{
			$obj = $reflectionClass->newInstanceArgs();
		}
		else
		{
			$obj = $reflectionClass->newInstanceArgs($dependencies);
		}

		return $obj;
	}

	private function getImplementingClass($interfaceName)
	{
		foreach ($this->implementingClasses as $className)
		{
			if (in_array($interfaceName, class_implements($className)))
			{
				return $className;
			}
		}

		throw new \Exception("No implementing class for $interfaceName specified.");
	}
}