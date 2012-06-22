<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class ExtensionlessUrlBuilder implements InternalUrlBuilder
{
	public function buildFrom($internalFile)
	{
		return $internalFile;
	}
}