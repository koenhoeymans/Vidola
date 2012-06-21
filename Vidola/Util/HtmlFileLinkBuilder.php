<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class HtmlFileLinkBuilder implements InternalLinkBuilder
{
	public function buildFrom($internalFile)
	{
		return $internalFile . '.html';
	}
}