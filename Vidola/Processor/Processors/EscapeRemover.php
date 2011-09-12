<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\Processor;

/**
 * @package Vidola
 */
class EscapeRemover implements Processor
{
	public function process($text)
	{
		return preg_replace_callback(
			"#,,,,(&(.)+;),,,,#U",
			function ($match)
			{
				# http://stackoverflow.com/questions/3005116/how-to-convert-all-characters-to-their-html-entity-equivalent-using-php/
				$convmap = array(0x0, 0xffff, 0, 0xffff);
				return mb_decode_numericentity($match[1], $convmap, 'UTF-8');
			},
			$text
		);
	}
}