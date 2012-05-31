<?php

/**
 * @package Fjor
 */
namespace Fjor\Dsl;

use Fjor\Fjor;

/**
 * @package Fjor
 */
class Dsl extends Fjor implements Given, ThenUse, InSingletonScope, ConstructWith
{
	private $given;

	private $thenUse;

	private $method;

	/**
	 * @return ThenUse|ConstructWith|andMethod
	 */
	public function given($classOrInterface)
	{
		$this->given = $classOrInterface;
		$this->thenUse = null;
		$this->method = null;

		return $this;
	}

	/**
	 * @return InSingletonScope|void
	 */
	public function thenUse($classOrInterfaceOrFactoryOrClosure)
	{
		$this->thenUse = $classOrInterfaceOrFactoryOrClosure;

		$this->addBinding($this->given,	$classOrInterfaceOrFactoryOrClosure);
		
		if (!is_object($classOrInterfaceOrFactoryOrClosure))
		{
			return $this;
		}

		return $this;
	}

	/**
	 * @return void
	 */
	public function inSingletonScope()
	{
		$this->setSingleton($this->thenUse);
	}

	/**
	 * @return void
	 */
	public function constructWith(array $values)
	{
		$target = ($this->thenUse === null) ? $this->given : $this->thenUse;
		$this->inject($target, '__construct', $values);
	}

	/**
	 * @return AddParam
	 */
	public function andMethod($method)
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * @return AddParam
	 */
	public function addParam(array $values = array())
	{
		$this->inject($this->given, $this->method, $values);
		return $this;
	}

	/**
	 * @return void
	 */
	public function setSingleton($classOrInterface)
	{
		parent::setSingleton($classOrInterface);
	}
}