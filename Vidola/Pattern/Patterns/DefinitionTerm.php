<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package
 */
class DefinitionTerm implements Pattern
{
	public function replace($text)
	{
		// note that we already know we're in a definition list
		$firstTermOfDefinitionReplaced = preg_replace_callback(
			"#(?<=^|\n)(([\ \t]*)(.+)):((\n\\2.+)*?(\n\\2[\ \t]+))#",
			function ($match)
			{
				return "$match[2]{{dt}}$match[3]{{/dt}}$match[4]";
			},
			$text
		);

		$otherTermsOfDefinitionReplaced = preg_replace_callback(
			"#(?<={{/dt}}\n)([^\ \t].*):(?=\n)#",
			function ($match)
			{
				return "{{dt}}$match[1]{{/dt}}";
			},
			$firstTermOfDefinitionReplaced
		);

		return $otherTermsOfDefinitionReplaced;
	}
}