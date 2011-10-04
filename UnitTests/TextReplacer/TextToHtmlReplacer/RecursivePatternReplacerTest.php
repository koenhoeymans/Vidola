<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_TextReplacer_TextToHtmlReplacer_RecursivePatternReplacerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->untaggedTextRegex =
			\Vidola\TextReplacer\TextToHtmlReplacer\RecursivePatternReplacer::untagged_text_regex;
		$this->betweenTagsRegex =
			\Vidola\TextReplacer\TextToHtmlReplacer\RecursivePatternReplacer::between_single_tags_regex;
		
	}

	public function replaceUntagged($text)
	{
		return preg_replace_callback(
			$this->untaggedTextRegex,
			function($match)
			{
				if ($match[1] === "") { return $match[2]; }
				return "*$match[1]*$match[2]";
			},
			$text
		);
	}

	public function replaceBetweenTags($text)
	{
		return preg_replace(
			$this->betweenTagsRegex,
			"\${1}*\${4}*\${5}",
			$text
		);
	}

	/**
	 * @test
	 */
	public function textWithoutTagsIsReturnedFully()
	{
		$text = "text without tags";
		$this->assertEquals(
			"*text without tags*",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function pcreBacktrackLimitNeedsToBeSetToAHigherValueThanDefault()
	{
		$text = "a link {{http://link}}.\n\nSome code:\n\nCODE:\n<?php // code goes here ?>\n\nEnd of this sample.";
		$this->assertEquals(
			"*a link {{http://link}}.\n\nSome code:\n\nCODE:\n<?php // code goes here ?>\n\nEnd of this sample.*",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function anElementInAStringIsLeftOut()
	{
		$text = "a{{a}}a{{/a}}a";
		$this->assertEquals(
			"*a*{{a}}a{{/a}}*a*",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function anElementAtStartOfAStringIsLeftOut()
	{
		$text = "{{a}}a{{/a}}text with an element";
		$this->assertEquals(
			"{{a}}a{{/a}}*text with an element*",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function anElementAtTheEndOfAStringIsLeftOut()
	{
		$text = "text with an{{a}}element{{/a}}";
		$this->assertEquals(
			"*text with an*{{a}}element{{/a}}",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function multipleElementsAreLeftOut()
	{
		$text = "this is{{a}}a{{/a}}text with{{b}}two{{/b}}elements";
		$this->assertEquals(
			"*this is*{{a}}a{{/a}}*text with*{{b}}two{{/b}}*elements*",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function multipleIdenticalElementsAreLeftOut()
	{
		$text = "start{{a}}a{{/a}}middle{{a}}a{{/a}}end";
		$this->assertEquals(
			"*start*{{a}}a{{/a}}*middle*{{a}}a{{/a}}*end*",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function differentNestedTagsAreLeftOut()
	{
		$text = "start{{a}}a{{b}}b{{/b}}a{{/a}}end";
		$this->assertEquals(
			"*start*{{a}}a{{b}}b{{/b}}a{{/a}}*end*",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function sameNestedTagsAreLeftOut()
	{
		$text = "start{{a}}xx{{a}}yy{{a}}kk{{/a}}ll{{/a}}zz{{/a}}end";
		$this->assertEquals(
			"*start*{{a}}xx{{a}}yy{{a}}kk{{/a}}ll{{/a}}zz{{/a}}*end*",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function attributesArePossibleOnOpeningTag()
	{
		$text = "start{{a id=\"a\"}}a{{/a}}end";
		$this->assertEquals(
			"*start*{{a id=\"a\"}}a{{/a}}*end*",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function stringCanStartWithTag()
	{
		$text = "{{a}}a{{/a}}end";
		$this->assertEquals(
			"{{a}}a{{/a}}*end*",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function stringCanEndWithTag()
	{
		$text = "start{{a}}a{{/a}}";
		$this->assertEquals(
			"*start*{{a}}a{{/a}}",
			$this->replaceUntagged($text)
		);
	}

	/**
	 * @test
	 */
	public function curlyBracketsTagsInCode()
	{
		$text =
"start{{pre}}{{code}}sub status {
	print \"working\";
}{{/code}}{{/end}}end";

		$replacement =
"*start*{{pre}}{{code}}sub status {
	print \"working\";
}{{/code}}{{/end}}*end*";

		$this->assertEquals($replacement, $this->replaceUntagged($text));
	}

	// -------------- text between tag ---------------------------

	/**
	 * @test
	 */
	public function matchTextBetweenSingleTag()
	{
		$text = "start{{a}}a{{/a}}end";
		$this->assertEquals(
			"start{{a}}*a*{{/a}}end",
			$this->replaceBetweenTags($text)
		);
	}

	/**
	 * @test
	 */
	public function matchTextBetweenSingleTagWithAttribute()
	{
		$text = "start{{a id=\"a\"}}a{{/a}}end";
		$this->assertEquals(
			"start{{a id=\"a\"}}*a*{{/a}}end",
			$this->replaceBetweenTags($text)
		);
	}

	/**
	 * @test
	 */
	public function matchTextBetweenTagThatIsEndOfString()
	{
		$text = "start{{a}}a\n\n{{/a}}";
		$this->assertEquals(
			"start{{a}}*a\n\n*{{/a}}",
			$this->replaceBetweenTags($text)
		);
	}

	/**
	 * @test
	 */
	public function matchTextBetweenTagThatIsStartOfString()
	{
		$text = "{{a}}a{{/a}}end";
		$this->assertEquals(
			"{{a}}*a*{{/a}}end",
			$this->replaceBetweenTags($text)
		);
	}

	/**
	 * @test
	 */
	public function matchTextBetweenMultipleTags()
	{
		$text = "start{{a}}a{{/a}}middle{{a}}a{{/a}}end";
		$this->assertEquals(
			"start{{a}}*a*{{/a}}middle{{a}}*a*{{/a}}end",
			$this->replaceBetweenTags($text)
		);
	}
}