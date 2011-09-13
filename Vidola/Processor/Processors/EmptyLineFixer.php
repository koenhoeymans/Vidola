<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\Processor;

/**
 * @package Vidola
 */
class EmptyLineFixer implements Processor
{
	public function process($text)
	{
		return preg_replace("#\n(\t| )+\n#", "\n\n", $text);
	}
}