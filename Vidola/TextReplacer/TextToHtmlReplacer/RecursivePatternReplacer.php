<?php

/**
 * @package Vidola
 */
namespace Vidola\TextReplacer\TextToHtmlReplacer;

use \Vidola\Pattern\Pattern;
use \Vidola\Pattern\PatternList;

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
	 * $match['before_element'] = text before tag or end of string
	 * $match['element'] = full element (including recursion) or end
	 */
	const untagged_text_regex =
		'@
		(?<before_element>.*)
			(?<element>
				{{(.(?![ ]/))+}}	# opening tag
				(()|(?(?={){[^{]|.)|(?R))+
				{{/.+}}				# closing tag
				|
				{{br[ ]/}}
				|
				{{hr[ ]/}}
				|
				{{img[ ].+[ ]/}}
				|
				$
			)
		@xUsD';

	/**
	 * $match[1] = opening tag
	 * $match[2] = text
	 * $match[5] = closing tag
	 */
	const between_single_tags_regex =
		"#({{([a-z0-9]+?)( [^}]+)?}})(.*?)({{/\\2}})#sD";

	private $text;

	private $pattern;

	private $patternList;

	public function __construct($text, Pattern $pattern, PatternList $patternList)
	{
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
		if ($regexMatch['before_element'] === '')
		{
			return '' . $regexMatch['element'];
		}
	
		$replaced = $this->pattern->replace($regexMatch['before_element']);

		if ($replaced !== $regexMatch['before_element']) // tags were inserted
		{
			// find text between tags and present to subpatterns
			$replaced = $this->replaceTextBetweenTags($replaced);
		}

		return $replaced . $regexMatch['element'];
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