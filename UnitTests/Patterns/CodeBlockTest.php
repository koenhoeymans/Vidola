<?php

require_once dirname(__FILE__)
. DIRECTORY_SEPARATOR . '..'
. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_CodeBlockTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Patterns\CodeBlock();
	}

	/**
	 * @test
	 */
	public function codeBlockIsWordCodeCapitalisedAndIndentedFollowedByColon()
	{
		$code = "\n\n\tCODE:\n\t\tthe code\n\n";
		$html = "\n\n<pre><code>the code</code></pre>\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function angledBracketsAreReplacedWithEntities()
	{
		$code = "text\n\n\tCODE:\n\t\ta <tag>\n\n";
		$html = "text\n\n<pre><code>a &lt;tag&gt;</code></pre>\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function codeBlocksKeepIndentationAsOutlined()
	{
		$code = "\n\n\tCODE:\n\t\tThis is code.\n\n\t\tThis is also code.\n\t\t\t\tThis line is indented.";
		$html = "\n\n<pre><code>This is code.\n\nThis is also code.\n\t\tThis line is indented.</code></pre>";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function codeShouldBeIndentedAfterCodeWord()
	{
		$code = "\n\n\tCODE:\n\tthe code\n\n";
		$html = "\n\n\tCODE:\n\tthe code\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function codeBlockStopsAfterBlanklineWithTextEquallyIndentedWithCodeWord()
	{
		$code = "\n\n\tCODE:\n\t\tThis is code.\n\n\tThis line is not code.";
		$html = "\n\n<pre><code>This is code.</code></pre>\n\n\tThis line is not code.";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function theCodeWordIsCaseInsensitive()
	{
		$code = "\n\n\tcoDe:\n\t\tthe code\n\n";
		$html = "\n\n<pre><code>the code</code></pre>\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function codeCanBeSurroundedByTwoLinesOfAtLeastThreeTildes()
	{
		$code = "\n\n~~~\nthe code\n~~~\n\n";
		$html = "\n\n<pre><code>the code</code></pre>\n\n";
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

<pre><code>example

~~~

code

~~~</code></pre>

";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function afterThreeTildesCanBeAnyText()
	{
		$code = "\n\n~~~ code ~~~\nthe code\n~~~~~~~~\n\n";
		$html = "\n\n<pre><code>the code</code></pre>\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function firstCharacterDeterminesIndentation()
	{
		$code = "\n\n~~~\n\tindented\n\t\tdoubleindented\n~~~\n\n";
		$html = "\n\n<pre><code>indented\n\tdoubleindented</code></pre>\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function wholeTildeCodeBlockCanBeIndented()
	{
		$code = "\n\n\t~~~\n\tthe code\n\t~~~\n\n";
		$html = "\n\n<pre><code>the code</code></pre>\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function tildeCodeBlockIsNonGreedy()
	{
		$code = "\n\n~~~\nthe code\n~~~\n\nparagraph\n\n~~~\ncode\n~~~\n\n";
		$html = "\n\n<pre><code>the code</code></pre>\n\nparagraph\n\n<pre><code>code</code></pre>\n\n";
		$this->assertEquals($html, $this->pattern->replace($code));
	}
}