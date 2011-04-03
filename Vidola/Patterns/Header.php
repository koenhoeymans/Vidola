<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Header implements Pattern
{
	/**
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{
		return preg_replace_callback(
			"#(\n\s*|^\s*)([-=+*^\#]{3,})?\n?\s*(.+)\n\s*((?<!\n\n)[-=+*^\#]{3,})(?=\n)#",
			function($match)
			{
				// @todo rethink when better closure support for $this
				static $headerList = array(
					1 => array('before' => null, 'after' => null),
					2 => array('before' => null, 'after' => null),
					3 => array('before' => null, 'after' => null),
					4 => array('before' => null, 'after' => null),
					5 => array('before' => null, 'after' => null),
					6 => array('before' => null, 'after' => null)
				);

				foreach ($headerList as $level => $header)
				{
					if ($header['after'] === null)
					{
						$headerList[$level]['before'] = $match[2];
						$headerList[$level]['after'] = $match[4];
						break;
					}
					if ($header['before'] === $match[2] && $header['after'] === $match[4])
					{
						break;
					}
				}

				return $match[1] . "<h" . $level . ">" . $match[3] . "</h" . $level . ">";
			},
			$text
		);
	}
}