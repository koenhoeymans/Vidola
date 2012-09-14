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
	 * Provides the parsed or raw content of a file.
	 * 
	 * @param string $page
	 * @param bool $raw
	 */
	public function getContent($page, $raw = false);
}