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
	 * Get all files that are subfiles of a give file.
	 * 
	 * @param string $file
	 * @return array
	 */
	public function getSubfiles($file);

	/**
	 * @param \DomDoc $domDoc
	 * @return \DomElement|null
	 */
	public function createTocNode(\DomDocument $domDoc);

	/**
	 * Creates the link pointing to a resource relative to a given resource. Both
	 * should be internal to the project and be absolute relative to the root of
	 * the project.
	 *
	 * @param string $to
	 * @param string $from
	 */
	public function createLink($toResource, $from = null);
}