<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_BoldTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->bold = new \Vidola\Patterns\Bold();
	}

	/**
	 * @test
	 */
	public function boldTextIsPlacedBetweenAsterisks()
	{
		$text = "This is a sentence with *bold* text.";
		$html = "This is a sentence with {{b}}bold{{/b}} text.";
		$this->assertEquals(
			$html, $this->bold->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function boldTextCanSpanMultipleWords()
	{
		$text = "This is a sentence with *bold text*.";
		$html = "This is a sentence with {{b}}bold text{{/b}}.";
		$this->assertEquals(
			$html, $this->bold->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleBoldSections()
	{
		$text = "This is *a sentence* with *bold text*.";
		$html = "This is {{b}}a sentence{{/b}} with {{b}}bold text{{/b}}.";
		$this->assertEquals(
			$html, $this->bold->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function aWordCannotContainBoldParts()
	{
		$text = "This is not a b*ol*d word.";
		$html = "This is not a b*ol*d word.";;
		$this->assertEquals(
			$html, $this->bold->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function multiplicationsDoNotInfluenceBoldText()
	{
		$text = "The result of 5*6, or 6 * 5 is 35, or *thirtyfive* in letters.";
		$html = "The result of 5*6, or 6 * 5 is 35, or {{b}}thirtyfive{{/b}} in letters.";
		$this->assertEquals(
			$html, $this->bold->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function firstAsteriskCannotHaveSpaceBehindIt()
	{
		$text = "This is not a sentence with * bold* text.";
		$html = "This is not a sentence with * bold* text.";
		$this->assertEquals(
			$html, $this->bold->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function lastAsteriskCannotHaveSpaceBeforeIt()
	{
		$text = "This is not a sentence with *bold * text.";
		$html = "This is not a sentence with *bold * text.";
		$this->assertEquals(
			$html, $this->bold->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function firstAsteriskMustBePrecededBySpace()
	{
		$text = "This is not a sentence with*bold* text.";
		$html = "This is not a sentence with*bold* text.";
		$this->assertEquals(
		$html, $this->bold->replace($text)
		);
	}
}