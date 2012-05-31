<?php

/**
 * @package Fjor
 */
namespace Fjor\ObjectFactory;

Use Fjor\Fjor;
Use Fjor\Injection\InjectionMap;

/**
 * @package Fjor
 */
interface ObjectFactory
{
	public function createInstance($class, InjectionMap $injections, Fjor $ioc);
}