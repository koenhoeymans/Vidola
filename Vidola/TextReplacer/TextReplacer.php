<?php

/**
 * @package Vidola
 */
namespace Vidola\TextReplacer;

use \Vidola\Processor\TextProcessor;
use \Vidola\Processor\DomProcessor;

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
	 * @param string $text
	 * @return string The replaced text.
	 */
	public function replace($text);

	/**
	 * @param Processor $processor
	 */
	public function addPreTextProcessor(TextProcessor $processor);

	/**
	 * @param Processor $processor
	 */
	public function addPostTextProcessor(TextProcessor $processor);

	/**
	 * @param DomProcessor $processor
	 */
	public function addPreDomProcessor(DomProcessor $processor);

	/**
	 * @param DomProcessor $processor
	 */
	public function addPostDomProcessor(DomProcessor $processor);
}