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
	public function write($text, $to)
	{
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