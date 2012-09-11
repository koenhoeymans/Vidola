<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_DefinitionDescriptionTest extends \Vidola\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->dd = new \Vidola\Pattern\Patterns\DefinitionDescription();
	}

	public function getPattern()
	{
		return $this->dd;
	}

	/**
	 * @test
	 */
	public function aDescriptionFollowsATermOnANewLineAndStartsAfterAColon()
	{
		$text =
'term a
: explanation
';

		$dom = new \DOMElement('dd', 'explanation');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function explanationCanContinueUnindentedOnNewLine()
	{
		$text =
'term a
: explanation on
multiple lines
';

		$dom = new \DOMElement('dd', "explanation on\nmultiple lines");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function explanationCanContinueIndentedOnNewLine()
	{
		$text =
'term a
: explanation on
  multiple lines
';

		$dom = new \DOMElement('dd', "explanation on\nmultiple lines");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function colonMayBeIndentedByUpToThreeSpaces()
	{
		$text =
'term a
   : explanation
';

		$dom = new \DOMElement('dd', 'explanation');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function descriptionCanBeTabIndented()
	{
		$text =
'term a
:	explanation on
 	multiple lines
';

		$dom = new \DOMElement('dd', "explanation on\nmultiple lines");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function descriptionCanBeAlignedWhenColonIsIndented()
	{
		$text =
'term a
   : explanation on
     multiple lines
';

		$dom = new \DOMElement('dd', "explanation on\nmultiple lines");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function moreThanOneDescriptionForATermIsPossible()
	{
		$text =
'term a
: explanation on
more than one line
: second explanation
: third explanation
';
		$dom = new \DOMElement('dd', "explanation on\nmore than one line");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function aDescriptionCanBeSharedByMultipleTerms()
	{
		$text =
'term a
term b
:   explanation on
    more than one line
';
		$dom = new \DOMElement('dd', "explanation on\nmore than one line");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function definitionsCanHaveMultipleParagraphs()
	{
		$text =
'term a
:	explanation on
	more than one line

	explanation continues with new paragraph
';

		$dom = new \DOMElement('dd', "explanation on\nmore than one line\n\nexplanation continues with new paragraph");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function defintionsCanHaveMultipleDescriptionsWithMultipleParagraphs()
	{
		$text =
'term a
:	explanation on
	more than one line

	explanation continues with new paragraph

:	second explanation
';

		$dom = new \DOMElement('dd', "explanation on\nmore than one line\n\nexplanation continues with new paragraph");
		$this->assertCreatesDomFromText($dom, $text);
	}
}