<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\Processor;
use Vidola\Pattern\Patterns\Header;

/**
 * @package Vidola
 * 
 * Fills header with syntax of level of headers.
 */
class HeaderFiller implements Processor
{
	private $header;

	public function __construct(Header $header)
	{
		$this->header = $header;
	}

	public function process($text)
	{
		$this->header->replace($text);

		return $text;
	}
}