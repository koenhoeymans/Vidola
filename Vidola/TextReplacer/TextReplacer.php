<?php

/**
 * @package Vidola
 */
namespace Vidola\TextReplacer;

use \Vidola\Processor\TextProcessor;

/**
 * @package vidola
 * 
 * Replaces the raw text with a certain format.
 */
interface TextReplacer
{
	/**
	 * The extension that belongs with the type of text.
	 * 
	 * @return string
	 */
	public function getExtension();

	/**
	 * @param Processor $processor
	 */
	public function addPreProcessor(TextProcessor $processor);

	/**
	 * @param Processor $processor
	 */
	public function addPostProcessor(TextProcessor $processor);

	/**
	 * @param string $text
	 * @return string The replaced text.
	 */
	public function replace($text);
}