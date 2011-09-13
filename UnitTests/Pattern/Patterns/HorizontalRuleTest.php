<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_HorizontalRuleTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\HorizontalRule();
	}

	/**
	 * @test
	 */
	public function atLeastThreeHyphensOnARuleByThemselvesProduceAHorizontalRule()
	{
		$text = "\n---\n";
		$html = "\n{{hr /}}\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function atLeastThreeAsteriskOnARuleByThemselvesProduceAHorizontalRule()
	{
		$text = "\n***\n";
		$html = "\n{{hr /}}\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function atLeastThreeUnderscoresOnARuleByThemselvesProduceAHorizontalRule()
	{
		$text = "\n___\n";
		$html = "\n{{hr /}}\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function spacingIsAllowed()
	{
		$text = "\n * * *\n";
		$html = "\n{{hr /}}\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function moreCharactersAreAllowed()
	{
		$text = "\n------------\n";
		$html = "\n{{hr /}}\n";
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function sameCharacterMustBeUsed()
	{
		$text = "\n-*-\n";
		$this->assertEquals($text, $this->pattern->replace($text));
	}
}