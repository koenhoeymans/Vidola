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
}