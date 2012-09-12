<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface DocumentationApiBuilder
{
	/**
	 * @param string $file
	 * @return Vidola\View\ViewApi
	 */
	public function buildApi($file);
}