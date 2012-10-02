<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface Content
{
	/**
	 * Get content parsed by the different patterns.
	 * 
	 * @param string $page
	 * @return \DomDocument
	 */
	public function getParsedContent($page);

	/**
	 * Get content as in file.
	 * 
	 * @param string $page
	 * @return string
	 */
	public function getRawContent($page);
}