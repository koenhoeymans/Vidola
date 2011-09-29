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
		return $this->replaceAtx($this->replaceSetext($text));
	}

	private function replaceSetext($text)
	{
		return preg_replace_callback(
			'@
			(?<start>(\n+|^)[ ]{0,3})
			(?<pre>[-=+*^\#]{3,})?
			\n?[ ]{0,3}(?<text>.+)\n[ ]{0,3}
			(?<post>(?<!\n\n)[-=+*^\#]{3,})
			(?=\n)
			@x',
			array($this, 'createSetextHeaders'),
			$text
		);
	}

	private function replaceAtx($text)
	{
		return preg_replace_callback(
			'@
			(?<start>(\n+|^)[ ]{0,3})
			(?<level>[#]{1,6})
			\ ?(?<text>.+?)
			(\ ?[#]+)?
			(?=\n)
			@x',
			function ($match)
			{
				$level = strlen($match['level']);
				$level = ($level > 5) ? 6 : $level;
				$start = ($match['start'] == '') ? '' : "\n\n";

				return $start
					. '{{h' . $level . '}}'
					. $match['text']
					. '{{/h' . $level . '}}';
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
				$this->headerList[$level]['before'] = substr($match['pre'], 0, 3);
				$this->headerList[$level]['after'] = substr($match['post'], 0, 3);
				break;
			}
			if ($header['before'] === substr($match['pre'], 0, 3)
				&& $header['after'] === substr($match['post'], 0, 3))
			{
				break;
			}
		}

		$id = str_replace(' ', '_', $match['text']);
		$start = ($match['start'] == '') ? '' : "\n\n";

		return $start . "{{h$level id=\"$id\"}}" . $match['text'] . "{{/h$level}}";
	}
}