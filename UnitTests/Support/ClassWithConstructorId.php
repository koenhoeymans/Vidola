<?php

namespace Vidola\UnitTests\Support;

class ClassWithConstructorId
{
	private $id;

	public function __construct($id)
	{
		$this->id = $id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}
}