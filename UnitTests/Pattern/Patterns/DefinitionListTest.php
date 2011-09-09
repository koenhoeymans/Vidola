<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_DefinitionListTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->dl = new \Vidola\Pattern\Patterns\DefinitionList();
	}

	/**
	 * @test
	 */
	public function aDlConsistsOfTermFollowedByIndentedDescriptionOnNewLine()
	{
		$text =
'paragraph

term:
	explanation

paragraph';

		$transformation =
'paragraph

{{dl}}
term:
	explanation
{{/dl}}

paragraph';

		$this->assertEquals($transformation, $this->dl->replace($text));
	}

	/**
	 * @test
	 */
	public function canBeStartOfInputString()
	{
				$text =
'term:
	explanation

paragraph';

		$transformation =
'{{dl}}
term:
	explanation
{{/dl}}

paragraph';

		$this->assertEquals($transformation, $this->dl->replace($text));
	}

	/**
	 * @test
	 */
	public function canBeEndOfInputString()
	{
				$text =
'paragraph

term:
	explanation';

		$transformation =
'paragraph

{{dl}}
term:
	explanation
{{/dl}}';

		$this->assertEquals($transformation, $this->dl->replace($text));
	}

	/**
	 * @test
	 */
	public function indentationDoesntMatterWhenPrecededByTwoBlankLines()
	{
		$text =
'paragraph


	term:
		explanation

paragraph';

		$transformation =
'paragraph

{{dl}}
term:
	explanation
{{/dl}}

paragraph';

		$this->assertEquals($transformation, $this->dl->replace($text));
	}

	/**
	 * @test
	 */
	public function whenOnlyOneBlankLineNoDefintionListWhenIndentedMoreThan3Spaces()
	{
		$text =
'paragraph

	term:
		explanation

paragraph';

		$transformation =
'paragraph

	term:
		explanation

paragraph';

		$this->assertEquals($transformation, $this->dl->replace($text));		
	}

	/**
	 * @test
	 */
	public function thereCanBeMultipleTermsAndDescriptionsWithParagraphs()
	{
				$text =
'paragraph

term a:
term b:
term c:
	~explanation x

	Continuation of explanation.

	~explanation y

	Continuation of explanation.

paragraph';

		$transformation =
'paragraph

{{dl}}
term a:
term b:
term c:
	~explanation x

	Continuation of explanation.

	~explanation y

	Continuation of explanation.
{{/dl}}

paragraph';

		$this->assertEquals($transformation, $this->dl->replace($text));
	}

	/**
	 * @test
	 */
	public function aDefinitionListCanContainMultipleTermsEachWithDescription()
	{
		$text =
'paragraph

term:
	explanation

other term:
	explanation

paragraph';

		$transformation =
'paragraph

{{dl}}
term:
	explanation

other term:
	explanation
{{/dl}}

paragraph';

		$this->assertEquals($transformation, $this->dl->replace($text));
	}

	/**
	 * @test
	 */
	public function aParagraphEndingWithSemicolonShouldNotBeMistakenForDefinition()
	{
		$text = "

This paragraph is followed by:

	code

";

		$this->assertEquals($text, $this->dl->replace($text));
	}
}