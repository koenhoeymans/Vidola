<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_SpecialCharacterTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\SpecialCharacter();
	}

	/**
	 * @test
	 */
	public function ampersandIsTranslatedToEntity()
	{
		$text = 'Example a&b and http://url.com?a=b&c=d for ampersands.';
		$html = 'Example a&amp;b and http://url.com?a=b&amp;c=d for ampersands.';
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function ampersandInEntityIsLeftAsIs()
	{
		$text = 'Entity for ampersand is &amp; and &copy; for copy.';
		$this->assertEquals($text, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function lesserThanOrGreaterThanAreTranslatedToEntities()
	{
		$text = 'Lesser than, or <, and greater than, or >, can be used like here: 4<5 or 5 > 4.';
		$html = 'Lesser than, or &lt;, and greater than, or &gt;, can be used like here: 4&lt;5 or 5 &gt; 4.';
		$this->assertEquals($html, $this->pattern->replace($text));
	}

	/**
	 * @test
	 */
	public function tagsAreLeftAsIs()
	{
		$text = 'Contains <em>emphasized</em> text.';
		$this->assertEquals($text, $this->pattern->replace($text));
	}
}