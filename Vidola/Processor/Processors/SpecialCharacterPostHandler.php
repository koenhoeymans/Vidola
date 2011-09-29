<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\Processor;

/**
 * @package Vidola
 */
class SpecialCharacterPostHandler implements Processor
{
	public function process($text)
	{
		# within code there should be no escaping as code should be meant literally
		$text = preg_replace_callback(
			'@
			<code>
			.+?
			</code>
			@xs',
			function ($match)
			{
				$a =  preg_replace("#,,,,(&(.)+;),,,,#U", "\\\\,,,,\${1},,,,", $match[0]);
				return $a;
			},
			$text
		);

		# decode entities that were encoded by preHandler
		$text = preg_replace_callback(
			'@
			,,,,(&(.)+;),,,,
			@xU',
			function ($match)
			{
				# http://stackoverflow.com/questions/3005116/how-to-convert-all-characters-to-their-html-entity-equivalent-using-php/
				$convmap = array(0x0, 0xffff, 0, 0xffff);
				$decoded = mb_decode_numericentity($match[1], $convmap, 'UTF-8');
				return $decoded;
			},
			$text
		);

		# htmlspecialchars, except for the < and > of tags unless withing code
		$text = preg_replace_callback(
			'@
			(?<text>.+?)
			(?<code>
			<code>
			(?<codetext>.+?)
			</code>
			|$)
			@xs',
			function ($match)
			{
				$text = preg_replace_callback(
					'@
					(?<text>.*?)
					(
					<
					(?<tag>/?[a-z].*?(\ /)?)
					>
					|
					$
					)
					@xsi',
					function ($match)
					{
						if (isset($match['tag']))
						{
							$tag = '<' . htmlspecialchars(
								$match['tag'], ENT_NOQUOTES, 'UTF-8', false
							) . '>';
						}
						else
						{
							$tag = '';
						}
						return htmlspecialchars($match['text'], ENT_NOQUOTES, 'UTF-8', false)
							. $tag;
					},
					$match['text']
				);
				if ($match['code'] != '')
				{
					$code = '<code>'
						. htmlspecialchars($match['codetext'], ENT_NOQUOTES, 'UTF-8', true)
						. '</code>';
				}
				else
				{
					$code = '';
				}

				return $text . $code;
			},
		$text
		);

		$text = preg_replace_callback(
			'@
			<([a-z][a-z-0-9]*?):::(.+?):::>
			@xis',
			function ($match)
			{
				$convmap = array(0x0, 0xffff, 0, 0xffff);
				$decoded = mb_decode_numericentity($match[2], $convmap, 'UTF-8');
				return '<' . $match[1] . $decoded . '>';
			},
			$text
		);

		return $text;
	}
}