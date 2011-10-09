<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\Processor;

/**
 * @package Vidola
 * 
 * Works in combination with EscapeRemover to hide the special meaning
 * of escaped characters.
 */
class SpecialCharacterPreHandler implements Processor
{
	/**
	 * @see Vidola\Processor.Processor::process()
	 */
	public function process($text)
	{
		$text = $this->neutralizeManuallyAddedTags($text);
		$text = $this->neutralizeCurlyBrackets($text);
		$text = $this->neutralizeEscapedChars($text);
		$text = $this->neutralizeCodeTags($text);

		return $text;
	}

	private function neutralizeManuallyAddedTags($text)
	{
		return preg_replace_callback(
			'@
			(<[a-z][a-z0-9]+)((([ ].+?=(\'|").+?(\'|"))(?=(>|[ ])))+)>
			@xi',
			function ($match)
			{
				# http://stackoverflow.com/questions/3005116/how-to-convert-all-characters-to-their-html-entity-equivalent-using-php/
				$convmap = array(0x0, 0xffff, 0, 0xffff);
				$encoded = mb_encode_numericentity($match[2], $convmap, 'UTF-8');

				return $match[1] . ':::' . $encoded . ':::>';
			},
			$text
		);
	}

	private function neutralizeCurlyBrackets($text)
	{
		$text = str_replace(
			'{{', ',,,,&#123;,,,,,,,,&#123;,,,,', $text
		);
		$text = str_replace(
			'}}', ',,,,&#125;,,,,,,,,&#125;,,,,', $text
		);

		return $text;
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

	/**
	 * If code contains </code> ... <code> we won't know anymore what the
	 * actual code is.
	 */
	private function neutralizeCodeTags($text)
	{
		return preg_replace("@<(/?code.*?)>@", "|||\${1}|||", $text);
	}
}