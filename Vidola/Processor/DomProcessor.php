<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor;

/**
 * @package Vidola
 */
interface DomProcessor
{
	public function process(\DOMDocument $domDocument);
}