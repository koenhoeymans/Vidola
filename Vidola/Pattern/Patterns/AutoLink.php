<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class AutoLink implements Pattern
{
	/**
	 * http://www.regular-expressions.info/email.html
	 */
	public function replace($text)
	{
		$email = preg_replace_callback(
			'@
			<([A-Z0-9._%+-]+\@[A-Z0-9.-]+\.[A-Z]{2,4})>
			@xi',
			function ($match)
			{
				return '{{a href="mailto:' . $match[1] . '"}}' . $match[1] . '{{/a}}';
			},
			$text
		);

		$url = preg_replace_callback(
			'@
			<(
			http://
			[A-Z0-9.-]+\.[A-Z]{2,4}
			(\S+)?
			)>
			@xi',
			function ($match)
			{
				return '{{a href="' . $match[1] . '"}}' . $match[1] . '{{/a}}';
			},
			$email
		);

		return $url;
	}
}