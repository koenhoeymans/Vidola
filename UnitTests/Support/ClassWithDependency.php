<?php

namespace Vidola\UnitTests\Support;

class ClassWithDependency
{
	public function __construct(ClassWithoutDependencies $dependency)
	{}
}