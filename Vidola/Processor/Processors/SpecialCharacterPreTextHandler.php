<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\TextProcessor;

/**
 * @package Vidola
 * 
 * Works in combination with EscapeRemover to hide the special meaning
 * of escaped characters.
 */
class SpecialCharacterPreTextHandler implements TextProcessor
{
	/**
	 * @see Vidola\Processor.Processor::process()
	 */
	public function process($text)
	{
		return $this->neutralizeEscapedChars($text);
	}

	private function neutralizeEscapedChars($text)
	{
		return preg_replace_callback(
			"#\\\\.#",
			function ($match)
			{
				# http://stackoverflow.com/questions/3005116/how-to-convert-all-characters-to-their-html-entity-equivalent-using-php/
				$convmap = array(0x0, 0xffff, 0, 0xffff);
				$encoded = mb_encode_numericentity($match[0][1], $convmap, 'UTF-8');

				return ',,,,' . $encoded . ',,,,';
			},
			$text
		);
	}
}