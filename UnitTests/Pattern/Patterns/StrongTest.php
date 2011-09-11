<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_StrongTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\Strong();
	}

	/**
	 * @test
	 */
	public function strongTextIsPlacedBetweenDoubleAsterisks()
	{
		$text = "This is a sentence with **strong** text.";
		$html = "This is a sentence with {{strong}}strong{{/strong}} text.";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function strongTextCanSpanMultipleWords()
	{
		$text = "This is a sentence with **strong text**.";
		$html = "This is a sentence with {{strong}}strong text{{/strong}}.";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleStrongSections()
	{
		$text = "This is **a sentence** with **strong text**.";
		$html = "This is {{strong}}a sentence{{/strong}} with {{strong}}strong text{{/strong}}.";
		$this->assertEquals(
			$html, $this->pattern->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function aWordCannotContainStrongParts()
	{
		$text = "This is not a st**ro**ng word.";
		$this->assertEquals($text, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function firstDoubleAsterisksCannotHaveSpaceBehindIt()
	{
		$text = "This is not a sentence with ** strong** text.";
		$this->assertEquals($text, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function lastDoubleAsterisksCannotHaveSpaceBeforeIt()
	{
		$text = "This is not a sentence with **strong ** text.";
		$this->assertEquals($text, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function firstAsteriskMustBePrecededBySpace()
	{
		$text = "This is not a sentence with**strong** text.";
		$this->assertEquals($text, $this->pattern->replace($text));
	}
}