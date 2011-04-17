<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class Writer
{
	/**
	 * Writes text to a specified file.
	 * 
	 * @param string $text
	 * @param string $to
	 * @throws \Exception
	 */
	public function write($text, $to)
	{
		if (!is_string($to))
		{
			throw new \Exception('Writer::write expects target to be a string format.');
		}

		$file = fopen($to, 'w');

		if (!$file)
		{
			throw new \Exception('Writer::write was unable to open ' . $to);
		}

		if(!fwrite($file, $text))
		{
			throw new \Exception('Writer::write was unable to write to ' . $to);
		}

		fclose($file);
	}
}