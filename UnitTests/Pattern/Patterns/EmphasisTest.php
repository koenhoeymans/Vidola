<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_EmphasisTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\Emphasis();
	}

	/**
	 * @test
	 */
	public function emphasizedTextIsPlacedBetweenAsterisks()
	{
		$text = "This is a sentence with *emphasized* text.";
		$html = "This is a sentence with {{em}}emphasized{{/em}} text.";
		$this->assertEquals(
			$html, $this->pattern->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function emphasizedTextCanSpanMultipleWords()
	{
		$text = "This is a sentence with *emphasized text*.";
		$html = "This is a sentence with {{em}}emphasized text{{/em}}.";
		$this->assertEquals(
			$html, $this->pattern->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleEmphasizedSections()
	{
		$text = "This is *a sentence* with *emphasized text*.";
		$html = "This is {{em}}a sentence{{/em}} with {{em}}emphasized text{{/em}}.";
		$this->assertEquals(
			$html, $this->pattern->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function aWordCannotContainEmphasizedParts()
	{
		$text = "This is not a b*ol*d word.";
		$html = "This is not a b*ol*d word.";;
		$this->assertEquals(
			$html, $this->pattern->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function multiplicationsDoNotInfluenceEmphasizedText()
	{
		$text = "The result of 5*6, or 6 * 5 is 35, or *thirtyfive* in letters.";
		$html = "The result of 5*6, or 6 * 5 is 35, or {{em}}thirtyfive{{/em}} in letters.";
		$this->assertEquals(
			$html, $this->pattern->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function firstAsteriskCannotHaveSpaceBehindIt()
	{
		$text = "This is not a sentence with * emphasized* text.";
		$html = "This is not a sentence with * emphasized* text.";
		$this->assertEquals(
			$html, $this->pattern->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function lastAsteriskCannotHaveSpaceBeforeIt()
	{
		$text = "This is not a sentence with *emphasized * text.";
		$html = "This is not a sentence with *emphasized * text.";
		$this->assertEquals(
			$html, $this->pattern->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function firstAsteriskMustBePrecededBySpace()
	{
		$text = "This is not a sentence with*emphasized* text.";
		$html = "This is not a sentence with*emphasized* text.";
		$this->assertEquals(
		$html, $this->pattern->replace($text)
		);
	}
}