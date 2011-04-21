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
 * Class used in HtmlBuilder to seperate the recursive logic from the class.
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
		"#(.*)(<(.+)( .+)?>([^<]|(?R))+</\\3>|$)#UsD";

	/**
	 * $match[1] + $match[2] + $match[5] = opening tag + text + closing tag
	 * 
	 * @var string
	 */
	const between_single_tags_regex =
		"#(<(?P<tag>[a-z0-9]+?)( [^>]+)?>)(.*?)(</(?P=tag)>)#sD";

	private $pattern;

	private $patternList;

	public function __construct(Pattern $pattern, PatternList $patternList)
	{
		$this->pattern = $pattern;
		$this->patternList = $patternList;
	}

	public static function using(Pattern $pattern, PatternList $patternList)
	{
		return new self($pattern, $patternList);
	}

	public function text($text)
	{
		return preg_replace_callback(
			self::untagged_text_regex,
			array($this, 'replaceUntaggedRegexMatched'),
			$text
		);
	}

	private function replaceUntaggedRegexMatched($regexMatch)
	{
		$replaced = $this->pattern->replace($regexMatch[1]);
		if ($replaced !== $regexMatch[1]) // tags were inserted because of match
		{
			// find text between tags and present to subpatterns
			$replaced = $this->presentTextBetweenTagsToSubpatterns($replaced);
		}

		return $replaced . $regexMatch[2];
	}

	private function presentTextBetweenTagsToSubpatterns($textBetweenTags)
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
			$text[4] = self::using($subpattern, $this->patternList)->text($text[4]);
		}
		return $text[1] . $text[4] . $text[5];
	}
}