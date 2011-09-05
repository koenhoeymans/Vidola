<?php

require_once dirname(__FILE__)
. DIRECTORY_SEPARATOR . '..'
. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_CodeIndentedTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Patterns\CodeIndented();
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

{{code}}code{{/code}}

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

{{code}}	a
b
	c{{/code}}

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

{{code}}code{{/code}}

";
		
		$this->assertEquals($html, $this->pattern->replace($code));		
	}

	/**
	* @test
	*/
	public function angledBracketsAreReplacedWithEntities()
	{
		$code = "text\n\n\ta <tag>\n\n";
		$html = "text\n\n{{code}}a &lt;tag&gt;{{/code}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}
}