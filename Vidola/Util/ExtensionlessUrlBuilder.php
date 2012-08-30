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
	/**
	 * @todo take relative links into accout
	 * @see Vidola\Util.InternalUrlBuilder::createLink()
	 */
	public function createLink($toResource, $from = null)
	{
		return $toResource;
	}
}