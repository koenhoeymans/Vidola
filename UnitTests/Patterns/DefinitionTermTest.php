<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_DefinitionTermTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->dt = new \Vidola\Patterns\DefinitionTerm();
	}

	/**
	 * @test
	 */
	public function aTermCanBeFollowedByADefinition()
	{
		$text =
'term a
	~explanation
';
		$transformation =
'<dt>term a</dt>
	~explanation
';

		$this->assertEquals($transformation, $this->dt->replace($text));		
	}

	/**
	 * @test
	 */
	public function aTermCanBeFollowedByAnotherTermSharingTheSameDefinition()
	{
				$text =
'term a
term b
	~explanation
';
		$transformation =
'<dt>term a</dt>
<dt>term b</dt>
	~explanation
';

		$this->assertEquals($transformation, $this->dt->replace($text));
	}

	/**
	 * @test
	 */
	public function termsCanHaveMoreThanOneDefinition()
	{
				$text =
'term a
	~explanation
	~explanation
';
		$transformation =
'<dt>term a</dt>
	~explanation
	~explanation
';

		$this->assertEquals($transformation, $this->dt->replace($text));
	}

	/**
	 * @test
	 */
	public function moreThanOneTermCanShareMoreThanOneDefinition()
	{
		$text =
'term a
term b
	~explanation
	~explanation
';
		$transformation =
'<dt>term a</dt>
<dt>term b</dt>
	~explanation
	~explanation
';

		$this->assertEquals($transformation, $this->dt->replace($text));
	}

	/**
	 * @test
	 */
	public function definitionListCanContainMoreThanOneTermWithoutNewlineBetweenPreviousDescriptionAndNewTerm()
	{
		$text =
'term a
	explanation
term b
	explanation
';
		$transformation =
'<dt>term a</dt>
	explanation
<dt>term b</dt>
	explanation
';

		$this->assertEquals($transformation, $this->dt->replace($text));
	}
}