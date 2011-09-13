<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_NewLineTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->newLine = new \Vidola\Pattern\Patterns\NewLine();
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
"Some text{{br /}}
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
"	Some text{{br /}}
	Other text";
		$this->assertEquals($html, $this->newLine->replace($text));		
	}

	/**
	 * @test
	 */
	public function doubleSpaceAtEndOfLineBecomesNewLine()
	{
		$text = "Some text before  \nand after double space";
		$html = "Some text before{{br /}}\nand after double space";
		$this->assertEquals($html, $this->newLine->replace($text));
	}
}