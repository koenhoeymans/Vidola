<?php

/**
 * @package Vidola
 */
namespace Vidola\TextReplacer;

use \Vidola\Patterns\Pattern;
use \Vidola\Patterns\PatternList;

// needed to avoid backtrack limit error for the regex
ini_set('pcre.backtrack_limit', 10000000);

/**
 * Class used in HtmlBuilder to seperate the recursive logic from the class. Would
 * be inner class if that was possible.
 * 
 * @package Vidola
 */
class RecursivePatternReplacer
{
	/**
	 * $match[1] = text before tag or end
	 * $match[2] = full element (including recursion) or end
	 * 
	 * @var string
	 */
	const untagged_text_regex =
		"#(.*)(<.+>([^<]|(?R))+</.+>|$)#UsD";

	/**
	 * $match[1] + $match[2] + $match[5] = opening tag + text + closing tag
	 * 
	 * @var string
	 */
	const between_single_tags_regex =
		"#(<([a-z0-9]+?)( [^>]+)?>)(.*?)(</\\2>)#sD";

	private $text;

	private $pattern;

	private $patternList;

	public function __construct($text, Pattern $pattern, PatternList $patternList)
	{
//var_dump($text);
		$this->text = $text;
		$this->pattern = $pattern;
		$this->patternList = $patternList;
	}

	public static function replaceRecursively(
		$text, Pattern $startPattern, PatternList $patternList
	) {
		$instance = new self($text, $startPattern, $patternList);
		$untaggedParts = $instance->findUntaggedPartsOfText();
		return $untaggedParts;
	}

	private function findUntaggedPartsOfText()
	{
		return preg_replace_callback(
			self::untagged_text_regex,
			array($this, 'replaceUntaggedPartsByPattern'),
			$this->text
		);
	}

	private function replaceUntaggedPartsByPattern($regexMatch)
	{
		if ($regexMatch[1] === '')
		{
			return '';
		}

		$replaced = $this->pattern->replace($regexMatch[1]);
		if ($replaced !== $regexMatch[1]) // tags were inserted
		{
			// find text between tags and present to subpatterns
			$replaced = $this->replaceTextBetweenTags($replaced);
		}

		return $replaced . $regexMatch[2];
	}

	private function replaceTextBetweenTags($textBetweenTags)
	{
		return preg_replace_callback(
			self::between_single_tags_regex,
			array($this, 'replaceTextBySubpatterns'),
			$textBetweenTags
		);
	}

	private function replaceTextBySubpatterns($text)
	{
		foreach($this->patternList->getSubpatterns($this->pattern) as $subpattern)
		{
			// if text is replaced by a previous subpattern we need to untag
			// => back at the beginning
			$text[4] = self::replaceRecursively(
				$text[4], $subpattern, $this->patternList
			);
		}

		return $text[1] . $text[4] . $text[5];
	}
}