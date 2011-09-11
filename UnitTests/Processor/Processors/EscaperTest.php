<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Processor_Processors_EscaperTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Processor\Processors\Escaper();
	}

	/**
	 * @test
	 */
	public function convertsEscapedCharacterToSomethingThatShouldNotInterfere()
	{
		$text = "These \*words* were e\\\scaped.";
		$html = "These ,,,,&#42;,,,,words* were e,,,,&#92;,,,,scaped.";
		$this->assertEquals($html, $this->pattern->process($text));
	}
}