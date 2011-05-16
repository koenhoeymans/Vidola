<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * Creates a Pattern with its Pattern dependencies.
 * 
 * @package Vidola
 */
class PatternCreator
{
	private $patterns = array();

	public function get($patternName)
	{
		$class = '\\Vidola\\Patterns\\' . ucfirst($patternName);

		if (isset($this->patterns[$class]))
		{
			return $this->patterns[$class];
		}

		$obj = $this->createObj($class);
		$this->patterns[$class] = $obj;

		return $obj;
	}

	private function createObj($className)
	{
		$reflectionClass = new \ReflectionClass($className);

		$reflectionClass = new \ReflectionClass($className);
		$dependencies = array();

		if ($reflectionClass->hasMethod('__construct'))
		{
			$reflectionMethod = $reflectionClass->getMethod('__construct');
			foreach ($reflectionMethod->getParameters() as $reflectionParameter)
			{
				$paramReflectionClass = $reflectionParameter->getClass();
				$paramClassName = $paramReflectionClass->getName();
				$paramObj = $this->get(substr(strrchr($paramClassName, '\\'), 1));

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
}