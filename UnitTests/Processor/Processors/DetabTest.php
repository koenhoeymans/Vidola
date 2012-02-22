<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Processor_Processors_DetabTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Processor\Processors\Detab();
	}

	/**
	 * @test
	 */
	public function changesTabsToSpaces()
	{
		$text = "para\n\t\npara";
		$html = "para\n    \npara";
		$this->assertEquals($html, $this->pattern->process($text));
	}
}