<?php

namespace Vidola\UnitTests\Support;

class ClassWithInterfaceDependency
{
	public function __construct(AnInterface $dependency)
	{}
}