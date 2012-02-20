<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_NewLineTest extends \Vidola\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\NewLine();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function doubleSpaceAtEndOfLineBecomesNewLine()
	{
		$text = "Some text before  \nand after double space";
		$dom = new \DOMElement('br');
		$this->assertCreatesDomFromText($dom, $text);
	}
}