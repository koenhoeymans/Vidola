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
		$text = $this->reAddBackslashInCode($text);
		$text = $this->decodeBracketAndEscapedCharsEntities($text);
		$text = $this->restoreTags($text);
		$text = $this->encodeSpecialCharsInCode($text);
		$text = $this->encodeSpecialCharsInRegularText($text);
		$text = $this->encodeSpecialCharsInUrls($text);
		$text = $this->restoreCodeTagsInCode($text);

		return $text;
	}

	private function reAddBackslashInCode($text)
	{
		return preg_replace_callback(
			'@<code>.+?</code>@s',
			function ($match)
			{
				return preg_replace("#,,,,(&(.)+;),,,,#U", "\\\\,,,,\${1},,,,", $match[0]);
			},
			$text
		);
	}

	# decode entities that were encoded by preHandler
	private function decodeBracketAndEscapedCharsEntities($text)
	{
		return preg_replace_callback(
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
	}

	private function restoreTags($text)
	{
		return preg_replace_callback(
			'@
			([a-z][a-z-0-9]*?):::(.+?):::
			@xis',
			function ($match)
			{
				$convmap = array(0x0, 0xffff, 0, 0xffff);
				$decoded = mb_decode_numericentity($match[2], $convmap, 'UTF-8');
				return $match[1] . $decoded;
			},
			$text
		);
	}

	/**
	 * In code special characters in the entities themselves are encoded too. 
	 */
	private function encodeSpecialCharsInCode($text)
	{
		return preg_replace_callback(
			'@
			(?<=<code>)
				(?<codetext>.+?)
			(?=</code>)
			@xs',
			function ($match)
			{
				return htmlspecialchars($match['codetext'], ENT_NOQUOTES, 'UTF-8', true);
			},
			$text
		);
	}

	private function encodeSpecialCharsInRegularText($text)
	{
		return preg_replace_callback(
			'@
			(?<text>.*?)				# text to encode followed by
			(?<non_encode>
				<code>.+?</code>		# code element
				|						# tag
				<\/?\w+((\s+\w+(\s*=\s*(?:\".*?\"|\'.*?\'|[^\'\">\s]+))?)+\s*|\s*)\/?>
				|
				<!--(.|\n)*?-->			# comments
				|
				$						# or end
			)
			@xsi',
			function ($match)
			{
				return htmlspecialchars($match['text'], ENT_NOQUOTES, 'UTF-8', false)
					. $match['non_encode'];
			},
			$text
		);
	}

	private function encodeSpecialCharsInUrls($text)
	{
		return preg_replace_callback(
			array(
				'@(<a[ ].*href=.)(.+)((\'|").*>)@iU',
				'@(<a[ ].*title=.)(.+)((\'|").*>)@iU',
				'@(<img[ ].*src.)(.+)((\'|").*>)@iU'
			),
			function ($match)
			{
				return
					$match[1]
					. htmlspecialchars($match[2], ENT_NOQUOTES, 'UTF-8', false)
					. $match[3];
			},
			$text
		);
	}

	private function restoreCodeTagsInCode($text)
	{
		return preg_replace("@\|\|\|(/?code.*?)\|\|\|@", "&lt;\${1}&gt;", $text);
	}
}