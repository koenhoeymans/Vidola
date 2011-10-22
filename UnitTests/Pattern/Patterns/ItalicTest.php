<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_ItalicTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->italic = new \Vidola\Pattern\Patterns\Italic();
	}

	/**
	 * @test
	 */
	public function italicTextIsPlacedBetweenUnderscores()
	{
		$text = "This is a sentence with _italicized_ text.";
		$html = "This is a sentence with {{i}}italicized{{/i}} text.";
		$this->assertEquals($html, $this->italic->replace($text));
	}

	/**
	 * @test
	 */
	public function italicTextCanContainMultipleWords()
	{
		$text = "This is a sentence with _italicized text_.";
		$html = "This is a sentence with {{i}}italicized text{{/i}}.";
		$this->assertEquals($html, $this->italic->replace($text));
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleItalicSections()
	{
		$text = "This is _a sentence_ with _italicized text_.";
		$html = "This is {{i}}a sentence{{/i}} with {{i}}italicized text{{/i}}.";
		$this->assertEquals(
			$html, $this->italic->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function aWordCannotContainAnItalicizedPart()
	{
		$text = "This word is not _ita_licized.";
		$this->assertEquals($text, $this->italic->replace($text));
	}

	/**
	 * @test
	 */
	public function firstUnderScoreMustBePrecededBySpace()
	{
		$text = "This is not an_italicized_ word.";
		$this->assertEquals($text, $this->italic->replace($text));
	}

	/**
	 * @test
	 */
	public function theFirstUnderscoreCannotHaveSpaceAfterIt()
	{
		$text = "This is not a sentence with _ italicized_ text.";
		$this->assertEquals($text, $this->italic->replace($text));
	}

	/**
	 * @test
	 */
	public function theLastUnderscoreCannotHaveSpaceBeforeIt()
	{
		$text = "This is not a sentence with _italicized _ text.";
		$this->assertEquals($text, $this->italic->replace($text));
	}

	/**
	 * @test
	 */
	public function onlyUnderscoresAreNotItalicized()
	{
		$text = "This is not a sentence with _____.";
		$this->assertEquals($text, $this->italic->replace($text));
	}
}