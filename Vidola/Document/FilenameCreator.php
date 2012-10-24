<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface FilenameCreator
{
	/**
	 * Create a filename for storing a page.
	 * 
	 * @param string $page
	 * @return string
	 */
	public function createFilename($page);
}