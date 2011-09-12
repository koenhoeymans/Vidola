<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class Header implements Pattern
{
	private $headerList = array(
		1 => array('before' => null, 'after' => null),
		2 => array('before' => null, 'after' => null),
		3 => array('before' => null, 'after' => null),
		4 => array('before' => null, 'after' => null),
		5 => array('before' => null, 'after' => null),
		6 => array('before' => null, 'after' => null)
	);

	public function replace($text)
	{
		return $this->replaceSetext($this->replaceAtx($text));
	}

	private function replaceSetext($text)
	{
		return preg_replace_callback(
			"#(\n\s*|^\s*)([-=+*^\#]{3,})?\n?\s*(.+)\n\s*((?<!\n\n)[-=+*^\#]{3,})(?=\n)#",
			array($this, 'createSetextHeaders'),
			$text
		);
	}

	private function replaceAtx($text)
	{
		return preg_replace_callback(
			'@
			(?<=^|\n)
			(?<level>[#]{1,6})
			\ (?<text>.+?)
			(\ [#]+)?
			(?=\n)
			@x',
			function ($match)
			{
				$level = strlen($match['level']);
				$level = ($level > 5) ? 6 : $level;

				return '<h' . $level . '>'
					. $match['text']
					. '</h' . $level . '>';
			},
			$text
		);
	}

	private function createSetextHeaders($match)
	{
		foreach ($this->headerList as $level => $header)
		{
			if ($header['after'] === null)
			{
				$this->headerList[$level]['before'] = substr($match[2], 0, 3);
				$this->headerList[$level]['after'] = substr($match[4], 0, 3);
				break;
			}
			if ($header['before'] === substr($match[2], 0, 3)
				&& $header['after'] === substr($match[4], 0, 3))
			{
				break;
			}
		}

		$id = str_replace(' ', '_', $match[3]);

		return $match[1] . "{{h$level id=\"$id\"}}$match[3]{{/h$level}}";
	}
}