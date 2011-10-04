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
	 * Escapes characters preceded by backslash. Converts these to numeric entity.
	 * The EscapeRemover decodes them back in the end. Thus they lose their special
	 * meaning while the conversion takes place.
	 * 
	 * @see Vidola\Processor.Processor::process()
	 */
	public function process($text)
	{
		$tagsNeutralized = preg_replace_callback(
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

		$leftCurlyBracketsNeutralized = str_replace(
			'{{', ',,,,&#123;,,,,,,,,&#123;,,,,', $tagsNeutralized
		);
		$rightCurlyBracketsNeutralized = str_replace(
			'}}', ',,,,&#125;,,,,,,,,&#125;,,,,', $leftCurlyBracketsNeutralized
		);

		$entities = preg_replace_callback(
			"#\\\\.#",
			function ($match)
			{
				# http://stackoverflow.com/questions/3005116/how-to-convert-all-characters-to-their-html-entity-equivalent-using-php/
				$convmap = array(0x0, 0xffff, 0, 0xffff);
				return ',,,,'
					. mb_encode_numericentity($match[0][1], $convmap, 'UTF-8')
					. ',,,,';
			},
			$rightCurlyBracketsNeutralized
		);

		return $entities;
	}
}