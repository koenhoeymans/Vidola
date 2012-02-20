<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_DefinitionTermTest extends \Vidola\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->dt = new \Vidola\Pattern\Patterns\DefinitionTerm();
	}

	public function getPattern()
	{
		return $this->dt;
	}

	/**
	 * @test
	 */
	public function aTermCanBeFollowedByADefinition()
	{
		$text =
'para

term a
:	explanation

para';

		$dom = new \DOMElement('dt', 'term a');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function aTermCanBeFollowedByAnotherTermSharingTheSameDefinition()
	{
				$text =
'para

term a
term b
:	explanation

para';

		$dom = new \DOMElement('dt', 'term a');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function termsCanHaveMoreThanOneDefinition()
	{
				$text =
'para

term a
:	explanation x
:	explanation y

para';

		$dom = new \DOMElement('dt', 'term a');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function moreThanOneTermCanShareMoreThanOneDefinition()
	{
		$text =
'para

term a
term b
:	explanation
:	explanation

para';

		$dom = new \DOMElement('dt', 'term a');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function definitionListCanContainMoreThanOneTermWithoutNewlineBetweenPreviousDescriptionAndNewTerm()
	{
		$text =
'para

term a
:	explanation
term b
:	explanation

para';

		$dom = new \DOMElement('dt', 'term a');
		$this->assertCreatesDomFromText($dom, $text);
	}
}