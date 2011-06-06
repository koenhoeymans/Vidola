<?php

namespace Vidola\UnitTests\Support;

class ClassWithDependencyDependency
{
	public function __construct(ClassWithDependency $dependency)
	{}
}