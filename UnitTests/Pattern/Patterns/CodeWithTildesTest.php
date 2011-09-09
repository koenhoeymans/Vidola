<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_CodeWithTildesTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\CodeWithTildes();
	}

	/**
	 * @test
	 */
	public function codeCanBeSurroundedByTwoLinesOfAtLeastThreeTildes()
	{
		$code = "\n\n~~~\nthe code\n~~~\n\n";
		$html = "\n\n{{code}}the code{{/code}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function tildeBlockCanContainRowOfTildesIfTheyAreIndented()
	{
		$code = "

~~~
	example

	~~~

	code

	~~~

~~~

";
		$html = "

{{code}}example

~~~

code

~~~{{/code}}

";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function afterThreeTildesCanBeAnyText()
	{
		$code = "\n\n~~~ code ~~~\nthe code\n~~~~~~~~\n\n";
		$html = "\n\n{{code}}the code{{/code}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function firstCharacterDeterminesIndentation()
	{
		$code = "\n\n~~~\n\tindented\n\t\tdoubleindented\n~~~\n\n";
		$html = "\n\n{{code}}indented\n\tdoubleindented{{/code}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function wholeTildeCodeBlockCanBeIndented()
	{
		$code = "\n\n\t~~~\n\tthe code\n\t~~~\n\n";
		$html = "\n\n{{code}}the code{{/code}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function tildeCodeBlockIsNonGreedy()
	{
		$code = "\n\n~~~\nthe code\n~~~\n\nparagraph\n\n~~~\ncode\n~~~\n\n";
		$html = "\n\n{{code}}the code{{/code}}\n\nparagraph\n\n{{code}}code{{/code}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function angledBracketsAreReplacedWithEntities()
	{
		$code = "text\n\n~~~\n\t\ta <tag>\n~~~\n\n";
		$html = "text\n\n{{code}}a &lt;tag&gt;{{/code}}\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}
}