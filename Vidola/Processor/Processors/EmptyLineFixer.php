<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\TextProcessor;

/**
 * @package Vidola
 */
class EmptyLineFixer implements TextProcessor
{
	public function process($text)
	{
		return preg_replace("#\n[\t ]+\n#", "\n\n", $text);
	}
}