<?php

namespace Vidola\UnitTests\Support;

class ClassWithConstructorId
{
	private $id;

	private $arrObj;

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

	public function setArrayObject(\ArrayObject $arrObj)
	{
		$this->arrObj = $arrObj;
	}

	public function getArrayObject()
	{
		return $this->arrObj;
	}
}