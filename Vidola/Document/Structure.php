<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package
 * @todo remove duplication from bundling up
 */
interface Structure
{
	/**
	 * Get a list of all pages in the project in order of appearance.
	 */
	public function getPages();

	/**
	 * Get all files that are subpages of a give page.
	 * 
	 * @param string $page
	 * @return array
	 */
	public function getSubpages($page);

	/**
	 * @param string $page
	 * @return \DomElement|null
	 */
	public function getToc($page);
}