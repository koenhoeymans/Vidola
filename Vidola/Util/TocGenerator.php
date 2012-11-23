<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package
 */
interface TocGenerator
{
	/**
	 * Creates a table of contents from the headers found in a DomDocument.
	 * 
	 * @param \DOMDocument $domDoc
	 * @return \DOMElement|null
	 */
	public function createTocNode(\DomDocument $domDoc);
}