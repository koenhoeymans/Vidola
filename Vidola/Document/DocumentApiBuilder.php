<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface DocumentApiBuilder
{
	/**
	 * @param string $file
	 * @return Vidola\View\ViewApi
	 */
	public function buildApi($file);
}