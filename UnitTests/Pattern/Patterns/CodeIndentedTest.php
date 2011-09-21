<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_CodeIndentedTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\CodeIndented();
	}

	/**
	 * @test
	 */
	public function indentedTextIsAlsoCode()
	{
		$code =
"paragraph

	code

paragraph";

		$html =
"paragraph

{{pre}}{{code}}code{{/code}}{{/pre}}

paragraph";

		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function variableIndentationIsPossibleWithinCode()
	{
		$code =
"paragraph

		a
	b
		c

paragraph";
		
		$html =
"paragraph

{{pre}}{{code}}	a
b
	c{{/code}}{{/pre}}

paragraph";
		
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function onlyBlankLinesBeforeAndAfterInStringAreSufficient()
	{
		$code =
"

	code

";
		
		$html =
"

{{pre}}{{code}}code{{/code}}{{/pre}}

";
		
		$this->assertEquals($html, $this->pattern->replace($code));		
	}
}