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

	private $bindValues = array();

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
	 * Set the values for a given method for a class. These values will be used
	 * upon instantation (or after the object is created).
	 * 
	 * @param array $values
	 * @param string $method
	 * @param string $className
	 */
	public function bind(array $values, $method, $className)
	{
		$this->bindValues[$className][$method] = $values;
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

		return $this->instances[$className] = $this->createObject($className);
	}

	private function createObject($className)
	{
		if (interface_exists($className))
		{
			$className = $this->getImplementingClass($className);
		}

		if (isset($this->instances[$className]))
		{
			return $this->instances[$className];
		}

		$obj = $this->instantiate($className);

		if (isset($this->bindValues[$className]))
		{
			foreach ($this->bindValues[$className] as $method => $args)
			{
				if ($method === '__construct') # been there, done that while constructing
				{
					continue;
				}
				call_user_func_array(array($obj, $method), $args);
			}
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

	private function instantiate($className)
	{
		$reflectionClass = new \ReflectionClass($className);
		$dependencies = array();
		
		if ($reflectionClass->hasMethod('__construct'))
		{
			## first check if any values have been given using 'bind'
			if (isset($this->bindValues[$className]['__construct']))
			{
				$dependencies = $this->bindValues[$className]['__construct'];
			}
		
			## try to find other values (eg set by 'willUse' or matching is available)
			$argumentPosition = 0;
			$reflectionMethod = $reflectionClass->getMethod('__construct');
			foreach ($reflectionMethod->getParameters() as $reflectionParameter)
			{
				if (isset($dependencies[$argumentPosition]))
				{
					continue;
				}
				$paramReflectionClass = $reflectionParameter->getClass();
				if (!$paramReflectionClass)
				{
					throw new \Exception(
					'No dependency specified for '
					. $className . ' on position ' . $argumentPosition
					);
				}
				$paramClassName = $paramReflectionClass->getName();
				$paramObj = $this->getInstance($paramClassName);
		
				$dependencies[$argumentPosition] = $paramObj;
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
}