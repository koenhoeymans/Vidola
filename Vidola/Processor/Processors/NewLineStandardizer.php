<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\TextProcessor;

/**
 * @package Vidola
 */
class NewLineStandardizer implements TextProcessor
{
	public function process($text)
	{
		return preg_replace("#\r\n?#", "\n", $text);
	}
}