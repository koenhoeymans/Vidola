<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Processor_Processors_SpecialCharacterPostHandlerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Processor\Processors\SpecialCharacterPostHandler();
	}

	/**
	 * @test
	 */
	public function removesBackslashFromText()
	{
		$text = "These ,,,,&#42;,,,,words* were e,,,,&#92;,,,,scaped.";
		$html = "These *words* were e\scaped.";
		$this->assertEquals($html, $this->pattern->process($text));
	}

	/**
	 * @test
	 */
	public function backslashIsReAddedWithinCode()
	{
		$text = "These <code>,,,,&#42;,,,,words* were e,,,,&#92;,,,,scaped</code>.";
		$html = "These <code>\*words* were e\\\scaped</code>.";
		$this->assertEquals($html, $this->pattern->process($text));
	}

	/**
	 * @test
	 */
	public function encodesAmpersands()
	{
		$text = "This text uses a&b.";
		$html = "This text uses a&amp;b.";
		$this->assertEquals($html, $this->pattern->process($text));
	}

	/**
	 * @test
	 */
	public function ampersandsWithinEntityAreLeftAsIs()
	{
		$text = "This text uses a&amp;b.";
		$this->assertEquals($text, $this->pattern->process($text));
	}

	/**
	 * @test
	 */
	public function ampersandWithinCodeIsEncoded()
	{
		$text = "These & <code>these & and these &amp;</code>.";
		$html = "These &amp; <code>these &amp; and these &amp;amp;</code>.";
		$this->assertEquals($html, $this->pattern->process($text));
	}

	/**
	 * @test
	 */
	public function unhashesStartTags()
	{
		$text = "A <span:::&#32;&#105;&#100;&#61;&#39;&#180;&#35;&#60;&#115;&#112;&#97;&#110;&#62;&#180;&#39;&#32;&#118;&#97;&#108;&#117;&#101;&#61;&#39;&#35;&#60;&#39;:::>span</span> element.";
		$html = "A <span id='´#<span>´' value='#<'>span</span> element.";
		$this->assertEquals($html, $this->pattern->process($text));
	}
}