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
		$text = $this->detab($text);
		$text = $this->neutralizeEscapedChars($text);

		return $text;
	}

	// adapted from PHP Markdown
	private function detab($text)
	{
		return preg_replace_callback(
			"/^.*?(?<space_before>[ ]?)\t.*$/m",
			function ($matches) {
				$line = $matches[0];
				$blocks = explode("\t", $line);
				$line = $blocks[0];
				unset($blocks[0]);
				foreach ($blocks as $block) {
					if ($matches['space_before'] === ' ')
					{
						$amount = 4;
					}
					else
					{
						// @todo set tab amount of spaces option
						$amount = 4 - mb_strlen($line, 'UTF-8') % 4;
					}
					$line .= str_repeat(" ", $amount) . $block;
				}
				return $line;
			},
			$text
		);
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