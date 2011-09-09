<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_sectionTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\SpecialSection('section:', 'section');
	}

	/**
	 * @test
	 */
	public function sectionsAreIntroducedByBlankLineWordAndColonWithTextIndentedOnFollowingLine()
	{
		$text =
"A paragraph.

section:
	some text

Another paragraph.";

		$html =
"A paragraph.

{{section}}
some text
{{/section}}

Another paragraph.";

		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function sectionsCanAlsoStartWithTwoBlankLinesAndWhateverIndentation()
	{
		$text =
"A paragraph.


	section:
		some text

Another paragraph.";

	$html =
"A paragraph.

{{section}}
some text
{{/section}}

Another paragraph.";
	
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function aBlankLineIsNotSufficientToStartAsectionIfPrecedingTextIsLessIndented()
	{
		$text =
"A paragraph.

	section:
		some text

Another paragraph.";

	$html =
"A paragraph.

	section:
		some text

Another paragraph.";
	
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function sectionsAreEndedByABlankLineFollowedByTextEquallyIndented()
	{
		$text =
"	A paragraph.


	section:
		some text

	Another paragraph.";

		$html =
"	A paragraph.

{{section}}
some text
{{/section}}

	Another paragraph.";

		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function sectionsAreEndedByABlankLineFollowedByTextLessIndented()
	{
		$text =
"	A paragraph.


	section:
		some text

Another paragraph.";

		$html =
"	A paragraph.

{{section}}
some text
{{/section}}

Another paragraph.";

		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function contentsIsUnindentedForLengthOfIndentationOfsectionWord()
	{
		$text =
"A paragraph.


	section:
			some text

Another paragraph.";
	
		$html =
"A paragraph.

{{section}}
	some text
{{/section}}

Another paragraph.";
	
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function textCanSpanMultipleLines()
	{
		$text =
"A paragraph.

section:
	some text
	continued on another line

Another paragraph.";

		$html =
"A paragraph.

{{section}}
some text
continued on another line
{{/section}}

Another paragraph.";

		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function textCanSpanMultipleLinesLazyStyle()
	{
		$text =
"A paragraph.

section:
	some text
continued on another line

Another paragraph.";

		$html =
"A paragraph.

{{section}}
some text
continued on another line
{{/section}}

Another paragraph.";

		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function asectionWithinAsectionIsLeftAsIs()
	{
		$text =
"A paragraph.

section:
	a section

	section:
		deeper nested section

another paragraph";

		$html =
"A paragraph.

{{section}}
a section

section:
	deeper nested section
{{/section}}

another paragraph";

		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function asectionCanContinueIndentedAfterNestedsection()
	{
		$text =
"A paragraph.

section:
	a section

	section continued

	section:
		deeper nested section

	section continued

another paragraph";

		$html =
"A paragraph.

{{section}}
a section

section continued

section:
	deeper nested section

section continued
{{/section}}

another paragraph";

		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function aClassNameCanBeSpecifified()
	{
		$section = new \Vidola\Pattern\Patterns\SpecialSection('section:', 'section', 'section');
		
		$text =
"A paragraph.

section:
	a section

another paragraph";

		$html =
"A paragraph.

{{section class=\"section\"}}
a section
{{/section}}

another paragraph";

		$this->assertEquals($html, $section->replace($text));
	}
}