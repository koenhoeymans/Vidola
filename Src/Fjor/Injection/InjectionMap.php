<?php

/**
 * @package Fjor
 */
namespace Fjor\Injection;

/**
 * @package Fjor
 */
class InjectionMap
{
	/**
	 * $injections = array(
	 * 	$method => array(
	 * 		array($firstParamsToInject),
	 * 		array($secondParamsToInject)
	 * 	)
	 * );
	 * 
	 * @var array
	 */
	private $injections = array();

	/**
	 * @return array
	 */
	public function getMethods()
	{
		return array_keys($this->injections);
	}

	/**
	 * @param string $method
	 * @return array
	 */
	public function getParams($method)
	{
		return isset($this->injections[$method]) ?
			$this->injections[$method] : array(array());
	}

	/**
	 * Parameters to inject for a given method. May be called
	 * more than once.
	 * 
	 * @param string $method
	 * @param array $params
	 */
	public function add($method, array $params)
	{
		$this->injections[$method][] = $params;
		return $this;
	}
}