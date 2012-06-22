<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class HtmlFileUrlBuilder implements InternalUrlBuilder
{
	public function buildFrom($internalFile)
	{
		$numberSignPos = strpos($internalFile, "#");

		if ($numberSignPos === false)
		{
			$filePart = $internalFile;
			$relPart = '';
		}
		else
		{
			$filePart = substr($internalFile, 0, $numberSignPos);
			$relPart = substr($internalFile, $numberSignPos);
		}

		return $filePart . '.html' . $relPart;
	}
}