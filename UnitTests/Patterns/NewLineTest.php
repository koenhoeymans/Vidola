<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_NewLineTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->newLine = new \Vidola\Patterns\NewLine();
	}

	/**
	 * @test
	 */
	public function aReturnOrNewLineAfterAndBeforeTextIsANewLine()
	{
		$text =
"Some text
Other text";
		$html =
"Some text<br />
Other text";
		$this->assertEquals($html, $this->newLine->replace($text));
	}

	/**
	 * @test
	 */
	public function indentationIsPreserved()
	{
		$text =
"	Some text
	Other text";
		$html =
"	Some text<br />
	Other text";
		$this->assertEquals($html, $this->newLine->replace($text));		
	}
}