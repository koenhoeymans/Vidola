<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Processor_Processors_SpecialCharacterPreHandlerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Processor\Processors\SpecialCharacterPreHandler();
	}

	/**
	 * @test
	 */
	public function convertsEscapedCharacterToSomethingThatShouldNotInterfereWithMarkup()
	{
		$text = "These \*words* were e\\\scaped.";
		$replacement = "These ,,,,&#42;,,,,words* were e,,,,&#92;,,,,scaped.";
		$this->assertEquals($replacement, $this->pattern->process($text));
	}

	/**
	 * @test
	 */
	public function hashesInTagsWhatCanBeMisunderstoodAsMarkup()
	{
		$text = "A <span id='´#<span>´' value='#<'>span</span> element.";
		$replacement = "A <span:::&#32;&#105;&#100;&#61;&#39;&#180;&#35;&#60;&#115;&#112;&#97;&#110;&#62;&#180;&#39;&#32;&#118;&#97;&#108;&#117;&#101;&#61;&#39;&#35;&#60;&#39;:::>span</span> element.";
		$this->assertEquals($replacement, $this->pattern->process($text));
	}

	/**
	 * @test
	 */
	public function neutralizesCurlyBracketsToNotInterfereWithVidolaTags()
	{
		$text = "This won't be {{mistaken for a tag}}.";
		$replacement = "This won't be ,,,,&#123;,,,,,,,,&#123;,,,,mistaken for a tag,,,,&#125;,,,,,,,,&#125;,,,,.";;
		$this->assertEquals($replacement, $this->pattern->process($text));
	}
}