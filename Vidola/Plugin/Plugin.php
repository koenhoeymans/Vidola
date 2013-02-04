<?php

/**
 * @package Vidola
 */
namespace Vidola\Plugin;

/**
 * @package Vidola
 */
abstract class Plugin
{
	abstract public function register(EventMapper $mapper);
}