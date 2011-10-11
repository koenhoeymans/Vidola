<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_ParagraphTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\Paragraph();
	}

	/**
	 * @test
	 */
	public function emptyLineThenTextThenEmptyLineIsParagraph()
	{
		$text = "\n\nparagraph\n\n";
		$html = "\n\n{{p}}paragraph{{/p}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function emptyLineThenTextThenLineBreakAndEndOfTextIsParagraph()
	{
		$text = "\n\nparagraph\n";
		$html = "\n\n{{p}}paragraph{{/p}}\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function emptyLineThenTextThenEndOfTextIsParagraph()
	{
		$text = "\n\nparagraph";
		$html = "\n\n{{p}}paragraph{{/p}}";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function canAlsoBeStartOfString()
	{
		$text = "paragraph\n\n";
		$html = "{{p}}paragraph{{/p}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function cannotBeBothStartAndEndOfString()
	{
		$text = "paragraph";
		$this->assertEquals($text, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function multipleParagraphsCanBePlacedAfterEachOther()
	{
		$text = "\n\nparagraph\n\nanother\n\nyet another\n\n";
		$html = "\n\n{{p}}paragraph{{/p}}\n\n{{p}}another{{/p}}\n\n{{p}}yet another{{/p}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function aParagraphCannotContainOnlyWhiteSpace()
	{
		$text = "\n\n  \n\n";
		$this->assertEquals($text, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function indentationOfThreeSpacesMaximum()
	{
		$text = "\n\n paragraph\n\n";
		$html = "\n\n{{p}}paragraph{{/p}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function indentedMoreThanThreeSpacesIsNoParagraph()
	{
		$text = "\n\n    paragraph\n\n";
		$this->assertEquals($text, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function indentedATabIsNoParagraph()
	{
		$text = "\n\n\tparagraph\n\n";
		$this->assertEquals($text, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function afterTwoBlankLinesIndentationOfFirstLineDoesntMatter()
	{
		$text = "\n\n\n\t\tparagraph\n\n";
		$html = "\n\n{{p}}paragraph{{/p}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function followingLinesCanBeIndentedTheSame()
	{
		$text =
"

 paragraph
 paragraph continued

";

		$html =
"

{{p}}paragraph
paragraph continued{{/p}}

";

		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function followingLinesCanBeLeftUnindented()
	{
		$text =
"

 paragraph
paragraph continued

";

		$html =
"

{{p}}paragraph
paragraph continued{{/p}}

";

		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function doesntMistakeTagsForParagraphs()
	{
		$text = "\n\n<div>\n\nparagraph\n\n</div>\n\n";
		$html = "\n\n<div>\n\n{{p}}paragraph{{/p}}\n\n</div>\n\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function aLesserThanSignCausesNoProblems()
	{
		$text = "\n\n<paragraph\n\n";
		$html = "\n\n{{p}}<paragraph{{/p}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function avoidsHTMLComments()
	{
		$text = "\n\n<!-- comment -->\n\n";
		$this->assertEquals($text, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function avoidsMultilineComments()
	{
		$text = "\n\n<!--\ncomment\n-->\n\n";
		$this->assertEquals($text, $this->pattern->replace($text));
	}
}