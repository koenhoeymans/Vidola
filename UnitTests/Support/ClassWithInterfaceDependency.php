<?php

namespace Vidola\UnitTests\Support;

class ClassWithInterfaceDependency
{
	private $dependency;

	public function __construct(AnInterface $dependency)
	{
		$this->dependency = $dependency;
	}

	public function getDependency()
	{
		return $this->dependency;
	}
}