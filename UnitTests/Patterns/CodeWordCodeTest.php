<?php

require_once dirname(__FILE__)
. DIRECTORY_SEPARATOR . '..'
. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_CodeWordCodeTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Patterns\CodeWordCode();
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
		$code =
"

	CODE:
		This is code.

	This line is not code.";
	
		$html =
"

<pre><code>This is code.</code></pre>

	This line is not code.";
	
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
}