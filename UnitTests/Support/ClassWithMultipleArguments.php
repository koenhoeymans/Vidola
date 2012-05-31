<?php

namespace Vidola\UnitTests\Support;

class ClassWithMultipleArguments
{
	private $arguments;

	public function __construct($foo, ClassWithInterfaceDependency $dependency, $bar)
	{
		$this->arguments = array($foo, $dependency, $bar);
	}

	public function getConstructorValues()
	{
		return $this->arguments;
	}
}