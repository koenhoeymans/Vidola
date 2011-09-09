<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_DefinitionDescriptionTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->dd = new \Vidola\Pattern\Patterns\DefinitionDescription();
	}

	/**
	 * @test
	 */
	public function aDescriptionFollowsATermOnANewLineAndStartsIndented()
	{
		$text =
'term a
	explanation on
	more than one line
';
		$transformation =
'term a
{{dd}}explanation on
more than one line{{/dd}}
';

		$this->assertEquals($transformation, $this->dd->replace($text));
	}

	/**
	 * @test
	 */
	public function moreThanOneDescriptionForATermIsPossibleWihtTheUseOfTildes()
	{
		$text =
'term a
	~explanation on
	more than one line
	~second explanation
';
		$transformation =
'term a
{{dd}}explanation on
more than one line{{/dd}}
{{dd}}second explanation{{/dd}}
';

		$this->assertEquals($transformation, $this->dd->replace($text));
	}

	/**
	 * @test
	 */
	public function aDescriptionCanBeSharedByMultipleTerms()
	{
		$text =
'term a
term b
	explanation on
	more than one line
';
		$transformation =
'term a
term b
{{dd}}explanation on
more than one line{{/dd}}
';

		$this->assertEquals($transformation, $this->dd->replace($text));
	}

	/**
	 * @test
	 */
	public function definitionsCanHaveMultipleParagraphs()
	{
		$text =
'term a
	explanation on
	more than one line

	explanation continues with new paragraph
';
		$transformation =
'term a
{{dd}}explanation on
more than one line

explanation continues with new paragraph{{/dd}}
';

		$this->assertEquals($transformation, $this->dd->replace($text));
	}

	/**
	 * @test
	 */
	public function defintionsCanHaveMultipleDescriptionsWithMultipleParagraphs()
	{
		$text =
'term a
	~explanation on
	more than one line

	explanation continues with new paragraph

	~second explanation
';
		$transformation =
'term a
{{dd}}explanation on
more than one line

explanation continues with new paragraph{{/dd}}

{{dd}}second explanation{{/dd}}
';

		$this->assertEquals($transformation, $this->dd->replace($text));
	}
}