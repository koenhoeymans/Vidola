<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Processor_Processors_EscapeRemoverTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Processor\Processors\EscapeRemover();
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
}