<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Processor_Processors_NewLineStandardizerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Processor\Processors\NewLineStandardizer();
	}

	/**
	 * @test
	 */
	public function allLineEndingsShouldBeUnixStandard()
	{
		$text = "para\rline\r\nother";
		$html = "para\nline\nother";
		$this->assertEquals($html, $this->pattern->process($text));
	}
}