<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
interface FileExtensionProvider
{
	/**
	 * Add an extension to the resource (if any).
	 * 
	 * @param string $resource
	 */
	public function addExtension($resource);
}