<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\Processor;

/**
 * @package Vidola
 */
class LineEndingsStandardizer implements Processor
{
	public function process($text)
	{
		return preg_replace("#\r\n?#", "\n", $text);
	}
}